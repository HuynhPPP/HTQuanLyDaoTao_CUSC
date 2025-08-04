
export interface Instructor {
    name: string;
    role: string;
    hours: number;
    note?: string;
}

export interface ExtractedInfo {
    classId: string;
    reportSession: string;
    date: string;
    time: string;
    location: string;
    groupCount: string;
    semester: string;
    instructors: Instructor[];
}


export interface SummaryInstructor {
    id: string;
    name: string;
    role: string;
    hours: number;
    notes: string;
    isChamDoAn?: boolean;
    stt?: number;
}

export interface SummaryEntry {
    id:string;
    date: string;
    timeRange: string;
    classInfo: string;
    instructors: SummaryInstructor[];
}

export interface SummaryData {
    id: string;
    month: number;
    year: number;
    entries: SummaryEntry[];
    preparer: string;
    approver: string;
    signatureDate: string;
}
