/// <reference types="vite/client" />
import { GoogleGenerativeAI } from "@google/generative-ai";
import { ExtractedInfo } from "../types.ts";

const API_KEY = import.meta.env.VITE_GEMINI_API_KEY;
if (!API_KEY) {
    console.error("VITE_GEMINI_API_KEY environment variable not set");
    throw new Error("VITE_GEMINI_API_KEY environment variable not set");
}

// Kiểm tra format của API key
if (API_KEY.length < 10) {
    console.error("API key seems too short, please check your configuration");
    throw new Error("Invalid API key format");
}

console.log("Gemini API key loaded successfully");
const genAI = new GoogleGenerativeAI(API_KEY);


const PROMPT = `
You are an expert data extraction tool. Your task is to analyze Vietnamese academic documents related to project defense sessions and extract specific information. The document could be one of two types:

1. **BẢNG PHÂN CÔNG CHẤM BÁO CÁO ĐỒ ÁN** (Assignment Grading Schedule) - Contains preliminary information
2. **BIÊN BẢN CHẤM BÁO CÁO ĐỒ ÁN** (Project Report Grading Minutes) - Contains official information (PRIORITY)

**CRITICAL INSTRUCTIONS:**

1. **JSON Only**: Your output must be a JSON object and nothing else. No introductory text, explanations, or markdown like \`\`\`json.
2. **Valid Syntax**: Ensure perfect JSON syntax. All keys and string values must be in double quotes. Objects and array elements must be separated by commas. Do not include any trailing commas.
3. **Data Types**: The \`hours\` field must be a number (e.g., \`1.50\`), not a string. Accept comma or dot in the source (e.g., "1,5"), but output must use dot decimal. All other fields should be strings.
4. **Required Fields**: All fields specified below are mandatory. If a field's value cannot be found in the image, use an empty string (\`""\`) for string values or \`0\` for numeric values.

**JSON OUTPUT STRUCTURE:**

\`\`\`json
{
  "classId": "string",
  "reportSession": "string", 
  "date": "string",
  "time": "string",
  "location": "string",
  "groupCount": "string",
  "semester": "string",
  "instructors": [
    {
      "name": "string",
      "role": "string", 
      "hours": 0,
      "note": "string"
    }
  ]
}
\`\`\`

**DETAILED EXTRACTION RULES:**

**Document Type Detection & Priority:**
- If you see "BIÊN BẢN CHẤM BÁO CÁO ĐỒ ÁN" → This is the PRIMARY source, use its information
- If you see "BẢNG PHÂN CÔNG CHẤM BÁO CÁO ĐỒ ÁN" → This is preliminary, use only if primary not available
- **CRITICAL TIME PRIORITY**: Always prioritize time information from "BIÊN BẢN CHẤM BÁO CÁO ĐỒ ÁN" over "BẢNG PHÂN CÔNG"
- **IMPORTANT**: A "BIÊN BẢN CHẤM BÁO CÁO ĐỒ ÁN" might be split into multiple images - process all if they contain relevant information
- **CRITICAL**: Each document should represent ONE session. If a document contains multiple projects, extract the session info but note all projects
- **PROCESS** any document that contains class information (CP codes), instructor names, or time/date information
- **IGNORE** only completely irrelevant documents (like general statistics without specific session data)

**Field Extraction Rules:**

* \`classId\`: Extract from "Lớp:" field. Look for patterns like "CP2496K04+CP2496J08" or "CP010120" or "CP24Y0G05". If multiple classes, use the first one or combine them with "+"
* \`reportSession\`: Extract from "Lần báo cáo:" field. Example: "1", "2", "02"
* \`date\`: Extract from "Ngày:" field. Format: "15/8/2025" or "15/08/2025". If not found, use empty string
* \`time\`: Extract time range. **PRIORITY ORDER**: 
    1. **FIRST PRIORITY**: If this is "BIÊN BẢN CHẤM BÁO CÁO ĐỒ ÁN", look for:
       - "Buổi báo cáo kết thúc lúc 11g30" or "11:30" (end time)
       - "Giờ bắt đầu: 7:30" and "Giờ kết thúc: 11:30" or similar patterns
       - Time ranges like "7:30-11:30", "8:00-9:30", "17:30-21:30"
       - Any time information in the "THỜI GIAN, ĐỊA ĐIỂM" section
    2. **SECOND PRIORITY**: If this is "BẢNG PHÂN CÔNG", look for:
       - "Giờ:" field with time ranges
       - Only use if no "BIÊN BẢN" information is available
    3. **FORMAT**: Always use "start-end" format like "7:30-11:30"
    4. **FALLBACK**: If no time found, use empty string
* \`location\`: Extract from "Địa điểm:" field. Example: "Phòng Lý thuyết 2"
* \`groupCount\`: Extract group count. Look for patterns like:
    - "03 Nhóm" → extract "03"
    - "01 nhóm" → extract "01" 
    - "3 nhóm" → extract "3"
    - "1 nhóm" → extract "1"
    - In table headers like "03 Nhóm - Lớp: CP2496K04+CP2496J08" → extract "03"
    - **IMPORTANT**: Always extract the actual number, not the word "nhóm"
* \`semester\`: Extract from "Học kỳ:" field. **IMPORTANT MAPPING**:
    * "1", "I", "01", "HK I", "Học kỳ I" → output "1" (maps to Năm 1)
    * "2", "II", "02", "HK II", "Học kỳ II" → output "2" (maps to Năm 1) 
    * "3", "III", "03", "HK III", "Học kỳ III" → output "3" (maps to Năm 2)
    * "4", "IV", "04", "HK IV", "Học kỳ IV" → output "4" (maps to Năm 2)

* \`instructors\`: Extract ALL instructor entries from the table. Look for these roles:
    * "Giáo viên hướng dẫn" (Instructor/Supervisor)
    * "Giáo viên phản biện" (Reviewer/Opponent) 
    * "Chấm đồ án" (Grading)
    * \`name\`: Extract exact name from "HỌ TÊN" or "HỌ VÀ TÊN" column
    * \`role\`: Extract exact role from "NHIỆM VỤ" or "CÔNG VIỆC" column
    * \`hours\`: Extract from "SỐ GIỜ" column as number. If empty, calculate from time range for GVHD/GVPB
    * \`note\`: Extract from "GHI CHÚ" column. **IMPORTANT**: If note contains "Năm 1" or "Năm 2", use that. Otherwise, map semester to year:
        * Semester 1,2 → "Năm 1"
        * Semester 3,4 → "Năm 2"

**SPECIAL INSTRUCTIONS:**

* **Time Priority**: If you see "Buổi báo cáo kết thúc lúc 17g00" in the document, use that as the end time for the time range
* **Data Consistency**: If bảng phân công shows 13:00-16:00 but biên bản shows end at 17:00, use 13:00-17:00
* **Instructor Roles**: Extract only roles that actually appear in the table
* **Year Mapping**: Always map semester to year level in the note field
* **Group Count**: Extract only the number (e.g., "03" from "03 Nhóm")
* **Multiple Sessions**: If document has multiple sessions, extract the FIRST one only

**ERROR HANDLING:**

* If the image is blurry or unreadable, extract what you can and use defaults for missing fields
* Always return a valid JSON object
* If no instructors found, return empty array
* If critical fields missing, use empty strings but maintain JSON structure
`;

const fileToBase64 = (file: File): Promise<string> => {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onloadend = () => {
            const base64String = (reader.result as string).split(",")[1];
            resolve(base64String);
        };
        reader.onerror = (error) => reject(error);
        reader.readAsDataURL(file);
    });
};

export const analyzeSingleDocument = async (
    imagePart: { inlineData: { mimeType: string; data: string } }
): Promise<ExtractedInfo | null> => {
    try {
        console.log("Starting analysis with Gemini API...");
        const model = genAI.getGenerativeModel({ model: "gemini-2.5-flash" });

        const result = await model.generateContent({
            contents: [
                {
                    role: "user",
                    parts: [
                        imagePart,
                        { text: PROMPT }
                    ]
                }
            ],
            generationConfig: {
                responseMimeType: "application/json",
            },
        });

        const response = await result.response;
        let jsonStr = response.text().trim();
        console.log("Raw response from Gemini:", jsonStr);

        // Gỡ bỏ ```json nếu có
        const fenceRegex = /^```(\w*)?\s*\n?(.*?)\n?\s*```$/s;
        const match = jsonStr.match(fenceRegex);
        if (match && match[2]) {
            jsonStr = match[2].trim();
            console.log("Removed JSON fence, cleaned response:", jsonStr);
        }

        const parsedData = JSON.parse(jsonStr) as ExtractedInfo;

        // Kiểm tra dữ liệu cơ bản
        if (!parsedData) {
            console.warn("Parsed data is null or undefined");
            return null;
        }

        // Kiểm tra các trường bắt buộc
        if (!parsedData.classId) {
            console.warn("Missing classId in parsed data:", parsedData);
            return null;
        }

        if (!Array.isArray(parsedData.instructors)) {
            console.warn("Missing or invalid instructors array in parsed data:", parsedData);
            return null;
        }

        // Kiểm tra ít nhất một instructor
        if (parsedData.instructors.length === 0) {
            console.warn("No instructors found in parsed data:", parsedData);
            return null;
        }

        console.log("Successfully parsed data:", parsedData);
        return parsedData;

    } catch (e) {
        console.error("Failed to analyze file:", e);
        if (e instanceof Error) {
            console.error("Error details:", e.message);
            console.error("Error stack:", e.stack);
        }
        return null;
    }
};

export const analyzeDocuments = async (files: File[]): Promise<(ExtractedInfo | null)[]> => {
    console.log(`Starting analysis of ${files.length} files...`);
    return Promise.all(
        files.map(async (file) => {
            try {
                console.log(`Processing file: ${file.name} (${file.type}, ${file.size} bytes)`);

                // Kiểm tra file type
                if (!file.type.startsWith('image/')) {
                    console.error(`File ${file.name} is not an image: ${file.type}`);
                    return null;
                }

                const base64Data = await fileToBase64(file);
                const imagePart = {
                    inlineData: {
                        mimeType: file.type,
                        data: base64Data,
                    },
                };
                const result = await analyzeSingleDocument(imagePart);
                console.log(`File ${file.name} analysis result:`, result ? "SUCCESS" : "FAILED");
                return result;
            } catch (error) {
                console.error(`Error processing file ${file.name}:`, error);
                return null;
            }
        })
    );
};

// Hàm kiểm tra API key và model availability
export const checkGeminiAPI = async (): Promise<{ success: boolean; message: string }> => {
    try {
        console.log("Checking Gemini API availability...");

        if (!API_KEY) {
            return { success: false, message: "API key not found" };
        }

        const model = genAI.getGenerativeModel({ model: "gemini-2.5-flash" });

        // Thử gọi API với một prompt đơn giản
        const result = await model.generateContent({
            contents: [{ role: "user", parts: [{ text: "Hello" }] }],
        });

        const response = await result.response;
        if (response.text()) {
            console.log("Gemini API is working correctly");
            return { success: true, message: "API is working" };
        } else {
            return { success: false, message: "API returned empty response" };
        }
    } catch (error) {
        console.error("Gemini API check failed:", error);
        return {
            success: false,
            message: error instanceof Error ? error.message : "Unknown error"
        };
    }
};
