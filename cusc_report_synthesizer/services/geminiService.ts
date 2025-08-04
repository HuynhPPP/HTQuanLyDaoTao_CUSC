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
You are an expert data extraction tool. Your task is to analyze the provided image of a document titled 'BẢNG THỐNG KÊ CÁC BUỔI CHẤM BÁO CÁO ĐỒ ÁN' and extract specific information. The final output must be a single, valid JSON object that follows the specified structure precisely.

**CRITICAL INSTRUCTIONS:**

1.  **JSON Only**: Your output must be a JSON object and nothing else. No introductory text, explanations, or markdown like \`\`\`json.
2.  **Valid Syntax**: Ensure perfect JSON syntax. All keys and string values must be in double quotes. Objects and array elements must be separated by commas. Do not include any trailing commas.
3.  **Data Types**: The \`hours\` field must be a number (e.g., \`1.50\`), not a string. All other fields should be strings.
4.  **Required Fields**: All fields specified below are mandatory. If a field's value cannot be found in the image, use an empty string (\`""\`) for string values or \`0\` for numeric values.

**JSON OUTPUT STRUCTURE:**

\`\`\`json
{
  "classId": "string",
  "reportSession": "string",
  "date": "string",
  "time": "string",
  "location": "string",
  "groupCount": "string",
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

* \`classId\`: Extract the class ID from the "Lớp:" field. Example: \`CP010120\`.
* \`reportSession\`: Extract the session number from the "Lớp:" field. Example: \`02\`.
* \`date\`: Extract the date from the "Ngày:" field. Example: \`20/06/2025\`.
* \`time\`: Extract the time range from the "Thời gian:" field. Example: \`9:00-11:00\`.
* \`location\`: Extract the location from the "Địa điểm:" field. Use \`""\` if not present.
* \`groupCount\`: Extract the group count from the "Lớp:" field. Example: \`01 nhóm\`.
* \`instructors\`: This is an array of objects. Extract ALL instructor entries from the table.
    * **IMPORTANT**: Extract ONLY the roles that appear in the table: "Giáo viên hướng dẫn", "Giáo viên phản biện", and "Chấm đồ án".
    * \`name\`: The full name from the "HỌ VÀ TÊN" column. Extract the exact name as shown, including any typos or misspellings.
    * \`role\`: The exact job title from the "CÔNG VIỆC" column. If you see "Giáo viên hướng dẫn", extract it as is. Do not correct spelling errors.
    * \`hours\`: The number of hours from the "SỐ GIỜ" column, as a number. Use the exact value shown.
    * \`note\`: The note from the "GHI CHÚ" column. Use \`""\` if the note is empty.

**SPECIAL INSTRUCTIONS:**

* Extract ONLY the instructors that are actually listed in the table
* Do NOT create additional entries for "Chấm đồ án" - this will be calculated later
* Use the exact hours as shown in the "SỐ GIỜ" column
* If there are no instructors in the table, return an empty array
* Pay attention to the session structure - each session may have multiple instructors
* If the document has multiple sessions, extract the FIRST session only
* Handle missing or unclear data gracefully by using default values
* **VERY IMPORTANT**: Extract the exact names as they appear in the "HỌ VÀ TÊN" column, including any typos
* **VERY IMPORTANT**: Extract the exact role names as they appear in the "CÔNG VIỆC" column, including any spelling errors
* **VERY IMPORTANT**: Use the exact hours from the "SỐ GIỜ" column for each role separately

**ERROR HANDLING:**

* If the image is blurry, unreadable, or a field is missing, extract what you can and fill the rest with empty or default values as specified. Your output must always be a valid JSON object.
* If you cannot extract any instructors, return an empty array for instructors.
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
