/// <reference types="vite/client" />
import { GoogleGenerativeAI } from "@google/generative-ai";
import { ExtractedInfo } from "../types.ts";

const API_KEY = import.meta.env.VITE_GEMINI_API_KEY;
if (!API_KEY) {
    throw new Error("VITE_GEMINI_API_KEY environment variable not set");
}
const genAI = new GoogleGenerativeAI(API_KEY);


const PROMPT = `
You are an expert data extraction tool. Analyze the provided image of a document titled 'BẢNG THỐNG KÊ CÁC BUỔI CHẤM BÁO CÁO ĐỒ ÁN'. Your task is to extract the specified information and format it as a single, valid JSON object.

**CRITICAL INSTRUCTIONS:**
1.  **JSON Only**: Your entire output must be ONLY the JSON object. Do not include any text, comments, or markdown like \`\`\`json.
2.  **Valid Syntax**: Ensure the JSON is perfectly valid. All strings must be in double quotes. All object properties and array items must be separated by commas. There must be no trailing commas.
3.  **Hours as Number**: The "hours" field must always be a number, not a string. The application will recalculate hours, but extract what you see.

**JSON OUTPUT STRUCTURE AND EXAMPLE:**
{"classId":"CP24Y0H06","reportSession":"02","date":"28/02/2025","time":"17:30-18:30","location":"","groupCount":"01 nhóm","instructors":[{"name":"Nguyễn Việt Nga","role":"Giáo viên hướng dẫn","hours":1.00,"note":""},{"name":"Võ Duy Anh","role":"Giáo viên phản biện","hours":1.00,"note":"Năm 1"}]}

**DETAILED FIELD EXTRACTION RULES:**
- "classId": From "Lớp:". Example: "CP24Y0H06".
- "reportSession": Session number from "Lớp:". Example: "02".
- "date": From "Ngày:". Example: "28/02/2025".
- "time": Time range from "Ngày:". Example: "17:30-18:30".
- "location": From "Địa điểm:". Use "" if not present.
- "groupCount": Group count from "Lớp:". Example: "01 nhóm".
- "instructors":
    - **IMPORTANT**: Extract only "Giáo viên hướng dẫn" and "Giáo viên phản biện" roles. DO NOT extract or create "Chấm đồ án" entries. The application will do that.
    - "name": From "HỌ VÀ TÊN".
    - "role": From "CÔNG VIỆC".
    - "hours": From "SỐ GIỜ" (as a number).
    - "note": From "GHI CHÚ". Convert "Học kỳ 1" to "Năm 1", and "Học kỳ 2" to "Năm 2". This note is critical and is usually on the "Giáo viên phản biện" line.

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
        const model = genAI.getGenerativeModel({ model: "gemini-2.0-flash" });

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

        // Gỡ bỏ ```json nếu có
        const fenceRegex = /^```(\w*)?\s*\n?(.*?)\n?\s*```$/s;
        const match = jsonStr.match(fenceRegex);
        if (match && match[2]) {
            jsonStr = match[2].trim();
        }

        const parsedData = JSON.parse(jsonStr) as ExtractedInfo;
        if (parsedData && parsedData.classId && Array.isArray(parsedData.instructors)) {
            return parsedData;
        }

        console.warn("Parsed data is missing required fields:", parsedData);
        return null;

    } catch (e) {
        console.error("Failed to analyze file:", e);
        return null;
    }
};

export const analyzeDocuments = async (files: File[]): Promise<(ExtractedInfo | null)[]> => {
    return Promise.all(
        files.map(async (file) => {
            try {
                const base64Data = await fileToBase64(file);
                const imagePart = {
                    inlineData: {
                        mimeType: file.type,
                        data: base64Data,
                    },
                };
                return await analyzeSingleDocument(imagePart);
            } catch (error) {
                console.error(`Error processing file ${file.name}:`, error);
                return null;
            }
        })
    );
};
