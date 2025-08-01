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
You are an expert data extraction tool. Analyze the provided image of a document titled 'BẢNG THỐNG KÊ CÁC BUỔI CHẤM BÁO CÁO ĐỒ ÁN'. Your task is to extract the specified information and format it as a single, valid JSON object.

**CRITICAL INSTRUCTIONS:**
1.  **JSON Only**: Your entire output must be ONLY the JSON object. Do not include any text, comments, or markdown like \`\`\`json.
2.  **Valid Syntax**: Ensure the JSON is perfectly valid. All strings must be in double quotes. All object properties and array items must be separated by commas. There must be no trailing commas.
3.  **Hours as Number**: The "hours" field must always be a number, not a string. The application will recalculate hours, but extract what you see.
4.  **Required Fields**: You MUST include all required fields. If a field is not found, use empty string "" or 0 as appropriate.

**JSON OUTPUT STRUCTURE AND EXAMPLE:**
{"classId":"CP24Y0H06","reportSession":"02","date":"28/02/2025","time":"17:30-18:30","location":"","groupCount":"01 nhóm","instructors":[{"name":"Nguyễn Việt Nga","role":"Giáo viên hướng dẫn","hours":1.00,"note":""},{"name":"Võ Duy Anh","role":"Giáo viên phản biện","hours":1.00,"note":"Năm 1"}]}

**DETAILED FIELD EXTRACTION RULES:**
- "classId": From "Lớp:". Example: "CP24Y0H06". REQUIRED.
- "reportSession": Session number from "Lớp:". Example: "02". REQUIRED.
- "date": From "Ngày:". Example: "28/02/2025". REQUIRED.
- "time": Time range from "Ngày:". Example: "17:30-18:30". REQUIRED.
- "location": From "Địa điểm:". Use "" if not present.
- "groupCount": Group count from "Lớp:". Example: "01 nhóm". REQUIRED.
- "instructors": Array of instructor objects. REQUIRED - must have at least one instructor.
    - **IMPORTANT**: Extract only "Giáo viên hướng dẫn" and "Giáo viên phản biện" roles. DO NOT extract or create "Chấm đồ án" entries. The application will do that.
    - "name": From "HỌ VÀ TÊN". REQUIRED.
    - "role": From "CÔNG VIỆC". REQUIRED.
    - "hours": From "SỐ GIỜ" (as a number). REQUIRED.
    - "note": From "GHI CHÚ". Convert "Học kỳ 1" to "Năm 1", and "Học kỳ 2" to "Năm 2". Use "" if empty.

**ERROR HANDLING:**
- If you cannot extract any required field, return a valid JSON with empty values rather than failing.
- If the image is unclear or unreadable, still attempt to extract what you can see.

**Final Check**: Before you provide the response, mentally validate the JSON. Is it 100% correct according to JSON specifications?
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
        const model = genAI.getGenerativeModel({ model: "gemini-1.5-flash" });

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
        
        const model = genAI.getGenerativeModel({ model: "gemini-1.5-flash" });
        
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
