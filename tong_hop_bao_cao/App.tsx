
import React, { useState, useCallback, useEffect } from 'react';
import { FileUpload } from './components/FileUpload.tsx';
import { SummaryTable } from './components/SummaryTable.tsx';
import { SavedReportsList } from './components/SavedReportsList.tsx';
import { analyzeDocuments, checkGeminiAPI } from './services/geminiService.ts';
import { ExtractedInfo, SummaryData, SummaryInstructor, Instructor } from './types.ts';
import { Header } from './components/Header.tsx';
import { UploadedFilesList } from './components/UploadedFilesList.tsx';
import { LoaderIcon, CheckCircleIcon } from './components/Icons.tsx';
import { initDB, getAllFiles, getAllReports, saveFile, deleteFile, saveReport, deleteReport, clearAllFiles, clearAllReports } from './db.ts';

const calculateHoursFromTimeRange = (timeRange: string): number => {
    if (!timeRange || !timeRange.includes('-')) return 0;

    const [start, end] = timeRange.split('-');
    if (!start || !end) return 0;

    const [startHour, startMinute] = start.split(':').map(Number);
    const [endHour, endMinute] = end.split(':').map(Number);

    if (isNaN(startHour) || isNaN(startMinute) || isNaN(endHour) || isNaN(endMinute)) {
        return 0;
    }

    const startDate = new Date(0);
    startDate.setHours(startHour, startMinute, 0, 0);

    const endDate = new Date(0);
    endDate.setHours(endHour, endMinute, 0, 0);

    let diff = endDate.getTime() - startDate.getTime();
    
    if (diff < 0) { // Handles overnight sessions, though unlikely for this use case
        endDate.setDate(endDate.getDate() + 1);
        diff = endDate.getTime() - startDate.getTime();
    }
    
    const hours = diff / (1000 * 60 * 60); // convert milliseconds to hours
    console.log(`Time calculation: ${timeRange} = ${hours} hours (${startHour}:${startMinute} to ${endHour}:${endMinute})`);
    return hours;
};


const App: React.FC = () => {
    const [uploadedFiles, setUploadedFiles] = useState<File[]>([]);
    const [selectedFiles, setSelectedFiles] = useState<File[]>([]);
    const [savedReports, setSavedReports] = useState<SummaryData[]>([]);
    const [summaryData, setSummaryData] = useState<SummaryData | null>(null);
    const [isAnalyzing, setIsAnalyzing] = useState<boolean>(false);
    const [isUploading, setIsUploading] = useState<boolean>(false);
    const [isDbLoading, setIsDbLoading] = useState<boolean>(true);
    const [error, setError] = useState<string | null>(null);
    const [apiStatus, setApiStatus] = useState<string | null>(null);

    useEffect(() => {
        const loadFromDb = async () => {
            try {
                await initDB();
                const [files, reports] = await Promise.all([getAllFiles(), getAllReports()]);
                setUploadedFiles(files);
                setSavedReports(reports.sort((a,b) => b.id.localeCompare(a.id))); // Show newest first
                 // Load last viewed report if any
                const lastViewedId = localStorage.getItem('cusc-last-viewed-report');
                if (lastViewedId) {
                    const lastViewed = reports.find(r => r.id === lastViewedId);
                    if (lastViewed) setSummaryData(lastViewed);
                }
            } catch (err) {
                console.error("Không thể tải dữ liệu từ cơ sở dữ liệu", err);
                setError("❌ Không thể tải dữ liệu đã lưu.\n\n💡 Thử:\n• Làm mới trang\n• Xóa dữ liệu cũ và tải lại tệp");
            } finally {
                setIsDbLoading(false);
            }
        };
        loadFromDb();
    }, []);

    useEffect(() => {
        // Persist edits to the currently viewed report
        if (summaryData) {
            saveReport(summaryData);
            localStorage.setItem('cusc-last-viewed-report', summaryData.id);
        }
    }, [summaryData]);

    // Function để validate dữ liệu trước khi tổng hợp
    const validateExtractedData = (infos: ExtractedInfo[]): ExtractedInfo[] => {
        return infos.filter(info => {
            // Kiểm tra dữ liệu cơ bản
            if (!info.classId || info.classId === "UNKNOWN") {
                console.warn("Invalid classId:", info.classId);
                return false;
            }
            
            if (!info.instructors || info.instructors.length === 0) {
                console.warn("No instructors found for class:", info.classId);
                return false;
            }
            
            // Xử lý tất cả tài liệu có thông tin hợp lệ
            // Cho phép xử lý tài liệu có classId là "UNKNOWN" vì có thể là bảng biên bản bị tách thành 2 ảnh
            const isRelevantDocument = info.classId && 
                                     (info.classId.includes('CP') || info.classId === 'UNKNOWN' || info.classId.trim() !== '') &&
                                     (info.time || info.date || info.instructors.length > 0 || info.reportSession);
            
            // Loại bỏ tài liệu có thông tin không đầy đủ hoặc không hợp lệ
            const hasValidTime = !info.time || (info.time.trim() !== '' && info.time !== 'Chưa xác định');
            const hasValidDate = !info.date || info.date.trim() !== '';
            const hasValidInstructors = info.instructors && info.instructors.length > 0;
            
            if (!hasValidTime || !hasValidDate || !hasValidInstructors) {
                console.warn("Skipping document with invalid data:", {
                    classId: info.classId,
                    time: info.time,
                    date: info.date,
                    instructorCount: info.instructors.length,
                    hasValidTime,
                    hasValidDate,
                    hasValidInstructors
                });
                return false;
            }
            
            if (!isRelevantDocument) {
                console.warn("Skipping irrelevant document:", {
                    classId: info.classId,
                    hasTime: !!info.time,
                    hasDate: !!info.date,
                    instructorCount: info.instructors.length
                });
                return false;
            }
            
            // Kiểm tra tính nhất quán của dữ liệu
            const hasValidHours = info.instructors.every(instructor => 
                typeof instructor.hours === 'number' && instructor.hours >= 0
            );
            
            if (!hasValidHours) {
                console.warn("Invalid hours data for class:", info.classId);
                return false;
            }
            
            // Kiểm tra xem có GVPB mà không có "Chấm đồ án" không
            const hasGVPB = info.instructors.some(instructor => 
                /phản\s*biện/i.test(instructor.role)
            );
            
            const hasChamDoAn = info.instructors.some(instructor => 
                /chấm\s*đồ\s*án/i.test(instructor.role)
            );
            
            // Kiểm tra họ tên giảng viên
            const hasValidNames = info.instructors.every(instructor => 
                instructor.name && instructor.name.trim().length > 0
            );
            
            if (!hasValidNames) {
                console.warn("Invalid instructor names for class:", info.classId);
                return false;
            }
            
            // Log thông tin để debug
            console.log(`Validation for ${info.classId}:`, {
                hasGVPB,
                hasChamDoAn,
                instructorCount: info.instructors.length,
                instructors: info.instructors.map(i => ({ 
                    name: i.name, 
                    role: i.role, 
                    hours: i.hours 
                }))
            });
            
            return true;
        });
    };

    const aggregateData = (infos: ExtractedInfo[]): SummaryData => {
        console.log("Starting data aggregation with", infos.length, "sessions");
        
        // Validate dữ liệu đầu vào
        const validInfos = validateExtractedData(infos);
        
        console.log(`Valid sessions to process: ${validInfos.length}/${infos.length}`);
        
        // Xử lý từng buổi báo cáo riêng biệt thay vì merge tất cả
        const processMultipleSessions = (infos: ExtractedInfo[]): ExtractedInfo[] => {
            if (infos.length === 0) {
                throw new Error("No valid data to process");
            }
            
            // Nhóm các tài liệu theo classId, date và time để xử lý từng buổi báo cáo riêng biệt
            const sessionGroups = new Map<string, ExtractedInfo[]>();
            
            infos.forEach(info => {
                // Tạo key duy nhất cho mỗi session dựa trên classId, date và time
                // Ưu tiên: classId + date + time để phân biệt các buổi khác nhau
                let key = '';
                if (info.date && info.date.trim() !== '' && info.time && info.time.trim() !== '' && info.time !== 'Chưa xác định') {
                    // Có đầy đủ thông tin: classId + date + time
                    key = `${info.classId}-${info.date}-${info.time}`;
                } else if (info.date && info.date.trim() !== '') {
                    // Chỉ có classId + date (không có time hoặc time không hợp lệ)
                    key = `${info.classId}-${info.date}`;
                } else if (info.reportSession && info.reportSession.trim() !== '') {
                    // Chỉ có classId + reportSession
                    key = `${info.classId}-session-${info.reportSession}`;
                } else {
                    // Fallback: sử dụng classId + index để tạo key duy nhất
                    key = `${info.classId}-${infos.indexOf(info)}`;
                }
                
                if (!sessionGroups.has(key)) {
                    sessionGroups.set(key, []);
                }
                sessionGroups.get(key)!.push(info);
            });
            
            console.log(`Found ${sessionGroups.size} distinct sessions:`, 
                Array.from(sessionGroups.keys())
            );
            
            // Loại bỏ các session trùng lặp và không hợp lệ
            const cleanedSessionGroups = new Map<string, ExtractedInfo[]>();
            
            for (const [key, sessionInfos] of sessionGroups) {
                // Kiểm tra session có hợp lệ không
                const hasValidTime = sessionInfos.some(info => 
                    info.time && info.time.trim() !== '' && info.time !== 'Chưa xác định'
                );
                
                const hasValidInstructors = sessionInfos.some(info => 
                    info.instructors && info.instructors.length > 0
                );
                
                // Chỉ giữ lại session có thông tin hợp lệ
                if (hasValidTime && hasValidInstructors) {
                    cleanedSessionGroups.set(key, sessionInfos);
                    console.log(`Keeping valid session: ${key}`);
                } else {
                    console.log(`Removing invalid session: ${key} (hasValidTime: ${hasValidTime}, hasValidInstructors: ${hasValidInstructors})`);
                }
            }
            
            console.log(`After cleaning: ${cleanedSessionGroups.size} valid sessions`);
            
            const processedSessions: ExtractedInfo[] = [];
            
            // Xử lý từng nhóm session riêng biệt
            for (const [sessionKey, sessionInfos] of cleanedSessionGroups) {
                console.log(`Processing session: ${sessionKey} with ${sessionInfos.length} documents`);
                
                // Tìm bảng biên bản (ưu tiên) và bảng phân công trong session này
                const biênBảnCandidates = sessionInfos.filter(info => {
                    // Ưu tiên tài liệu có thông tin thời gian chi tiết (thường là biên bản)
                    const hasDetailedTimeInfo = info.time && (
                        info.time.includes('7:30') || info.time.includes('8:00') || 
                        info.time.includes('11:30') || info.time.includes('9:30') ||
                        info.time.includes('17:30') || info.time.includes('21:30') ||
                        info.time.includes('21:15') || info.time.includes('17:00')
                    );
                    
                    const hasInstructors = info.instructors?.some(inst => 
                        inst.role?.includes('hướng dẫn') || inst.role?.includes('phản biện')
                    );
                    
                    return (info.classId && info.classId.includes('CP')) && 
                           (hasDetailedTimeInfo || hasInstructors);
                });
                
                // Tìm bảng phân công (không phải bảng biên bản)
                const phânCông = sessionInfos.find(info => 
                    info.classId && info.classId.includes('CP') && 
                    !biênBảnCandidates.includes(info)
                );
                
                // Chọn bảng biên bản chính (có thông tin đầy đủ nhất)
                const biênBản = biênBảnCandidates.find(info => 
                    info.time?.includes('17:00') || info.time?.includes('13:00')
                ) || biênBảnCandidates[0];
                
                console.log(`Session ${sessionKey} documents:`, { 
                    biênBản: biênBản ? `${biênBản.classId} (${biênBản.time})` : 'none',
                    phânCông: phânCông ? `${phânCông.classId} (${phânCông.time})` : 'none'
                });
                
                // Sử dụng bảng biên bản làm cơ sở, bổ sung từ bảng phân công nếu cần
                const baseInfo = biênBản || phânCông || sessionInfos[0];
                
                // Tạo dữ liệu tổng hợp cho session này
                const mergedInfo: ExtractedInfo = {
                    classId: baseInfo.classId,
                    reportSession: baseInfo.reportSession,
                    date: baseInfo.date || '', // Cho phép empty string
                    time: baseInfo.time || '', // Cho phép empty string
                    location: baseInfo.location || '',
                    groupCount: baseInfo.groupCount || '',
                    semester: baseInfo.semester || '',
                    instructors: [...baseInfo.instructors]
                };

                // Chuẩn hóa số nhóm: nếu cả hai nguồn có số nhóm, lấy giá trị lớn hơn
                const parseGroupCount = (gc?: string): number => {
                    if (!gc) return 0;
                    const m = gc.match(/(\d+)/);
                    return m ? parseInt(m[1], 10) : 0;
                };
                const groupsFromBienBan = parseGroupCount(biênBản?.groupCount);
                const groupsFromPhanCong = parseGroupCount(phânCông?.groupCount);
                if (groupsFromBienBan > 0 || groupsFromPhanCong > 0) {
                    const chosen = Math.max(groupsFromBienBan, groupsFromPhanCong);
                    if (chosen > 0) {
                        mergedInfo.groupCount = String(chosen);
                        console.log(`Session ${sessionKey} normalized groupCount:`, {
                            groupsFromBienBan,
                            groupsFromPhanCong,
                            chosen
                        });
                    }
                }
                
                // Merge thông tin từ tất cả bảng biên bản (có thể bị tách thành nhiều ảnh)
                if (biênBảnCandidates.length > 0) {
                    console.log(`Session ${sessionKey} merging info from biên bản candidates:`, biênBảnCandidates.length);
                    
                    // Tìm thông tin tốt nhất từ tất cả bảng biên bản
                    for (const candidate of biênBảnCandidates) {
                        // Ưu tiên thời gian từ biên bản (sẽ được xử lý riêng ở bước sau)
                        if (candidate.location && !mergedInfo.location) {
                            mergedInfo.location = candidate.location;
                            console.log(`Session ${sessionKey} added location from biên bản:`, candidate.location);
                        }
                        if (candidate.semester && !mergedInfo.semester) {
                            mergedInfo.semester = candidate.semester;
                            console.log(`Session ${sessionKey} added semester from biên bản:`, candidate.semester);
                        }
                        if (candidate.groupCount && !mergedInfo.groupCount) {
                            mergedInfo.groupCount = candidate.groupCount;
                            console.log(`Session ${sessionKey} added groupCount from biên bản:`, candidate.groupCount);
                        }
                    }
                }
                
                // Bổ sung thông tin từ bảng phân công nếu có và bảng biên bản thiếu
                if (phânCông) {
                    console.log(`Session ${sessionKey} merging additional info from phân công:`, {
                        location: phânCông.location,
                        semester: phânCông.semester,
                        groupCount: phânCông.groupCount,
                        time: phânCông.time
                    });
                    
                    // Bổ sung thông tin thiếu từ bảng phân công
                    if (!mergedInfo.location && phânCông.location) {
                        mergedInfo.location = phânCông.location;
                        console.log(`Session ${sessionKey} added location from phân công:`, phânCông.location);
                    }
                    if (!mergedInfo.semester && phânCông.semester) {
                        mergedInfo.semester = phânCông.semester;
                        console.log(`Session ${sessionKey} added semester from phân công:`, phânCông.semester);
                    }
                    if (!mergedInfo.groupCount && phânCông.groupCount) {
                        mergedInfo.groupCount = phânCông.groupCount;
                        console.log(`Session ${sessionKey} added groupCount from phân công:`, phânCông.groupCount);
                    }
                }
                
                // Điều chỉnh thời gian - ƯU TIÊN TUYỆT ĐỐI thông tin từ bảng biên bản
                let bestTime = null;
                
                // BƯỚC 1: Tìm thời gian từ bảng biên bản (ưu tiên cao nhất)
                for (const candidate of biênBảnCandidates) {
                    if (candidate.time && candidate.time.trim() !== '') {
                        // Ưu tiên thời gian có format đầy đủ (start-end) từ biên bản
                        if (candidate.time.includes('-') && candidate.time.length > 8) {
                            bestTime = candidate.time;
                            console.log(`Session ${sessionKey} found complete time range from BIÊN BẢN:`, candidate.time);
                            break;
                        }
                    }
                }
                
                // BƯỚC 2: Nếu không có thời gian đầy đủ từ biên bản, tìm thời gian cụ thể
                if (!bestTime) {
                    for (const candidate of biênBảnCandidates) {
                        if (candidate.time && candidate.time.trim() !== '') {
                            // Ưu tiên các thời gian cụ thể từ biên bản
                            if (candidate.time.includes('11:30') || candidate.time.includes('9:30') || 
                                candidate.time.includes('21:30') || candidate.time.includes('21:15')) {
                                bestTime = candidate.time;
                                console.log(`Session ${sessionKey} found specific end time from BIÊN BẢN:`, candidate.time);
                                break;
                            }
                        }
                    }
                }
                
                // BƯỚC 3: Chỉ sử dụng bảng phân công nếu KHÔNG có thông tin từ biên bản
                if (!bestTime && phânCông && phânCông.time && phânCông.time.trim() !== '' && phânCông.time !== 'Chưa xác định') {
                    console.log(`Session ${sessionKey} using time from BẢNG PHÂN CÔNG (fallback):`, phânCông.time);
                    if (phânCông.time.includes('16:00')) {
                        mergedInfo.time = phânCông.time.replace('16:00', '17:00');
                        console.log(`Session ${sessionKey} adjusted time from phân công: 16:00 -> 17:00`);
                    } else {
                        mergedInfo.time = phânCông.time;
                    }
                } else if (bestTime) {
                    mergedInfo.time = bestTime;
                    console.log(`Session ${sessionKey} using time from BIÊN BẢN (priority):`, bestTime);
                } else {
                    // Nếu không có thời gian hợp lệ, bỏ qua session này
                    console.warn(`Session ${sessionKey} has no valid time information, skipping`);
                    continue;
                }
                
                console.log(`Session ${sessionKey} final merged data:`, {
                    classId: mergedInfo.classId,
                    time: mergedInfo.time,
                    groupCount: mergedInfo.groupCount,
                    instructorCount: mergedInfo.instructors.length
                });
                
                processedSessions.push(mergedInfo);
            }
            
            return processedSessions;
        };
        
        const processedSessions = processMultipleSessions(validInfos);
        
        // Xử lý từng buổi báo cáo riêng biệt
        const processSingleSession = (info: ExtractedInfo) => {
            console.log(`Processing merged session:`, info);
            
            // Cải thiện parsing số nhóm - lấy từ mergedInfo đã chuẩn hóa
            let groupCountNum = 1;
            if (info.groupCount) {
                    const simpleMatch = info.groupCount.match(/(\d+)/);
                    if (simpleMatch) {
                        groupCountNum = parseInt(simpleMatch[1], 10);
                    }
                }
            
            console.log(`Merged session group count: ${groupCountNum} (from: ${info.groupCount})`);
            
            const sessionDuration = calculateHoursFromTimeRange(info.time);
            
            console.log(`Merged session details:`, {
                classId: info.classId,
                groupCount: groupCountNum,
                sessionDuration: sessionDuration,
                instructors: info.instructors.length
            });
            
            // Hàm xác định năm học từ thông tin lớp, instructors và semester
            const determineYearLevel = (classId: string, instructors: Instructor[], semester?: string): string => {
                // Ưu tiên sử dụng thông tin semester với mapping mới
                if (semester) {
                    const semNum = parseInt(semester, 10);
                    if (semNum === 1 || semNum === 2) return 'Năm 1';
                    if (semNum === 3 || semNum === 4) return 'Năm 2';
                }
                
                // Tìm ghi chú có chứa thông tin năm học
                const normalizeSemesterText = (text: string): string => {
                    // Chuẩn hóa các biến thể I/1, II/2, III/3, IV/4, HK I/II/III/IV
                    let t = text;
                    t = t.replace(/HK\s*I\b|Học\s*kỳ\s*I\b/gi, 'Học kỳ 1');
                    t = t.replace(/HK\s*II\b|Học\s*kỳ\s*II\b/gi, 'Học kỳ 2');
                    t = t.replace(/HK\s*III\b|Học\s*kỳ\s*III\b/gi, 'Học kỳ 3');
                    t = t.replace(/HK\s*IV\b|Học\s*kỳ\s*IV\b/gi, 'Học kỳ 4');
                    t = t.replace(/Học\s*kỳ\s*0?1\b/gi, 'Học kỳ 1');
                    t = t.replace(/Học\s*kỳ\s*0?2\b/gi, 'Học kỳ 2');
                    t = t.replace(/Học\s*kỳ\s*0?3\b/gi, 'Học kỳ 3');
                    t = t.replace(/Học\s*kỳ\s*0?4\b/gi, 'Học kỳ 4');
                    return t;
                };

                const yearNote = instructors.find(inst => {
                    const note = inst.note ? normalizeSemesterText(inst.note) : '';
                    return note.includes('Năm 1') || note.includes('Năm 2') || 
                           note.includes('Học kỳ 1') || note.includes('Học kỳ 2') || 
                           note.includes('Học kỳ 3') || note.includes('Học kỳ 4') ||
                           note.includes('Year 1') || note.includes('Year 2');
                })?.note;
                
                if (yearNote) {
                    const nn = normalizeSemesterText(yearNote);
                    if (nn.includes('Năm 2') || nn.includes('Học kỳ 3') || nn.includes('Học kỳ 4') || nn.includes('Year 2')) {
                        return 'Năm 2';
                    }
                    if (nn.includes('Năm 1') || nn.includes('Học kỳ 1') || nn.includes('Học kỳ 2') || nn.includes('Year 1')) {
                        return 'Năm 1';
                    }
                }
                
                // Fallback: phân tích từ mã lớp
                const classIdUpper = classId.toUpperCase();
                if (classIdUpper.includes('CP01') || classIdUpper.includes('CP010') || classIdUpper.includes('CP2023') || classIdUpper.includes('CP2024') || classIdUpper.includes('CP2025')) {
                    return 'Năm 1';
                }
                if (classIdUpper.includes('CP24') || classIdUpper.includes('CP25') || classIdUpper.includes('CP2022') || classIdUpper.includes('CP2021')) {
                    return 'Năm 2';
                }
                if (classIdUpper.includes('CP23') || classIdUpper.includes('CP2020') || classIdUpper.includes('CP2019')) {
                    return 'Năm 3';
                }
                return 'Năm 1';
            };
            // Xác định năm học cho session này
            const yearLevel = determineYearLevel(info.classId, info.instructors, info.semester);
            console.log(`Merged session year level:`, yearLevel, `(semester: ${info.semester})`);
            
            // Kiểm tra xem đã có vai trò "Chấm đồ án" trong dữ liệu chưa
            const hasChamDoAn = info.instructors.some(instructor => 
                /chấm\s*đồ\s*án/i.test(instructor.role)
            );
            
            console.log(`Merged session already has "Chấm đồ án":`, hasChamDoAn);
            
            // Sử dụng dữ liệu chính xác từ Gemini
            const allInstructors: SummaryInstructor[] = [];
            
            info.instructors.forEach((instructor, index) => {
                console.log(`Processing instructor ${index + 1}:`, instructor);
                
                // Validate instructor data - chỉ cần tên và vai trò
                if (!instructor.name || !instructor.role) {
                    console.warn("Skipping instructor with missing name or role:", instructor);
                    return;
                }
                
                // Tạo ID duy nhất cho mỗi instructor
                const instructorId = `inst-${Date.now()}-${Math.random()}-${index}`;
                
                // Tính toán số giờ theo vai trò (luôn tính lại để đảm bảo chính xác)
                let hours = 0;
                
                if (/hướng\s*dẫn/i.test(instructor.role)) {
                        // GVHD: số giờ = thời gian buổi báo cáo
                        hours = sessionDuration;
                    console.log(`GVHD ${instructor.name}: calculated hours = ${hours} (session duration: ${info.time})`);
                    } else if (/phản\s*biện/i.test(instructor.role)) {
                    // GVPB: số giờ = thời gian buổi báo cáo
                        hours = sessionDuration;
                    console.log(`GVPB ${instructor.name}: calculated hours = ${hours} (session duration: ${info.time})`);
                    } else if (/chấm\s*đồ\s*án/i.test(instructor.role)) {
                        // Chấm đồ án: tính theo công thức
                        let chamDoAnHours = 1.5; // Mặc định cho Năm 1
                        if (yearLevel === 'Năm 2') {
                            chamDoAnHours = 2.0;
                        } else if (yearLevel === 'Năm 3') {
                            chamDoAnHours = 2.5;
                        }
                        hours = chamDoAnHours * groupCountNum;
                    console.log(`Chấm đồ án ${instructor.name}: calculated hours = ${hours} (${chamDoAnHours} × ${groupCountNum} groups, ${yearLevel})`);
                } else {
                    // Các vai trò khác: sử dụng số giờ từ dữ liệu gốc hoặc 0
                    hours = instructor.hours || 0;
                    console.log(`Other role ${instructor.name}: using provided hours = ${hours}`);
                }
                
                // Xử lý ghi chú - cải thiện để linh hoạt hơn
                let note = instructor.note || '';
                // Chuẩn hóa các biến thể học kỳ I/II/III/IV -> 1/2/3/4
                note = note.replace(/HK\s*I\b|Học\s*kỳ\s*I\b/gi, 'Học kỳ 1');
                note = note.replace(/HK\s*II\b|Học\s*kỳ\s*II\b/gi, 'Học kỳ 2');
                note = note.replace(/HK\s*III\b|Học\s*kỳ\s*III\b/gi, 'Học kỳ 3');
                note = note.replace(/HK\s*IV\b|Học\s*kỳ\s*IV\b/gi, 'Học kỳ 4');
                
                // Mapping học kỳ sang năm học theo yêu cầu mới
                if (/học\s*kỳ\s*[12]|học\s*kỳ\s*0?[12]/i.test(note)) note = 'Năm 1';
                if (/học\s*kỳ\s*[34]|học\s*kỳ\s*0?[34]/i.test(note)) note = 'Năm 2';
                
                // Nếu ghi chú chưa có thông tin năm học, sử dụng yearLevel đã xác định
                if (!note.includes('Năm 1') && !note.includes('Năm 2') && !note.includes('Năm 3')) {
                    note = yearLevel;
                }
                
                // Thêm instructor vào danh sách
                allInstructors.push({
                    id: instructorId,
                    name: instructor.name,
                    role: instructor.role,
                    hours: hours,
                    notes: note,
                    isChamDoAn: /chấm\s*đồ\s*án/i.test(instructor.role)
                });
            });
            
            // Chỉ thêm vai trò "Chấm đồ án" nếu chưa có trong dữ liệu và có GVPB
            if (!hasChamDoAn) {
                const gvpb = info.instructors.find(instructor => 
                    /phản\s*biện/i.test(instructor.role)
                );
                
                if (gvpb) {
                    // Tính số giờ chấm đồ án theo công thức - cải thiện cho nhiều năm học
                    let chamDoAnHours = 1.5; // Mặc định cho Năm 1
                    
                    if (yearLevel === 'Năm 2') {
                        chamDoAnHours = 2.0;
                    } else if (yearLevel === 'Năm 3') {
                        chamDoAnHours = 2.5; // Có thể điều chỉnh theo quy định
                    }
                    
                    const totalChamDoAnHours = chamDoAnHours * groupCountNum;
                    
                    console.log(`Adding "Chấm đồ án" for ${gvpb.name}:`, {
                        yearLevel: yearLevel,
                        chamDoAnHours: chamDoAnHours,
                        groupCount: groupCountNum,
                        totalHours: totalChamDoAnHours
                    });
                    
                    allInstructors.push({
                        id: `inst-${Date.now()}-${Math.random()}-chamdoan`,
                        name: gvpb.name,
                        role: 'Chấm đồ án',
                        hours: totalChamDoAnHours,
                        notes: yearLevel,
                        isChamDoAn: true
                    });
                }
            } else {
                console.log(`Merged session already has "Chấm đồ án" entries, skipping creation`);
            }
            
            console.log(`Merged session final instructors:`, allInstructors);
            
            // Chuẩn hóa hiển thị số nhóm: luôn là 2 chữ số + " Nhóm"
            const groupCountDisplay = `${String(groupCountNum).padStart(2, '0')} Nhóm`;

            return {
                id: `entry-${Date.now()}-${Math.random()}`,
                date: info.date || 'Chưa xác định',
                timeRange: info.time || 'Chưa xác định',
                classInfo: `${info.classId} (${groupCountDisplay})-Lần ${info.reportSession || '1'}`,
                instructors: allInstructors
            };
        };
        
        // Tạo nhiều entries từ các sessions đã xử lý
        const classEntries = processedSessions.map(session => processSingleSession(session));
        
        // Xác định tháng mới nhất dựa trên ngày của phiên hợp lệ
        const parseVietnamDate = (dateStr: string): number => {
            const m = dateStr.match(/(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{2,4})/);
            if (!m) return -Infinity;
            const day = parseInt(m[1], 10);
            const month = parseInt(m[2], 10) - 1;
            let year = parseInt(m[3], 10);
            if (year < 100) year += 2000;
            return new Date(year, month, day).getTime();
        };

        // Tìm ngày mới nhất từ tất cả sessions
        const allDates = processedSessions
            .filter(session => session.date && session.date.trim() !== '')
            .map(session => parseVietnamDate(session.date));
        
        let newestDate = new Date(); // Mặc định là ngày hiện tại
        if (allDates.length > 0) {
            const newestDateMs = Math.max(...allDates);
            if (isFinite(newestDateMs)) {
                newestDate = new Date(newestDateMs);
            }
        }

        const result = {
            id: `summary-${Date.now()}`,
            entries: classEntries, // Nhiều entries cho nhiều buổi báo cáo
            month: newestDate.getMonth() + 1,
            year: newestDate.getFullYear(),
            signatureDate: '',
            preparer: '',
            approver: ''
        };
        
        console.log("Final aggregated data:", result);
        return result;
    };
    
    const checkAPI = useCallback(async () => {
        setApiStatus("Đang kiểm tra API...");
        try {
            const result = await checkGeminiAPI();
            if (result.success) {
                setApiStatus("✅ API hoạt động bình thường");
                setTimeout(() => setApiStatus(null), 3000);
            } else {
                setApiStatus(`❌ Lỗi API: ${result.message}`);
            }
        } catch (error) {
            setApiStatus(`❌ Lỗi kiểm tra API: ${error instanceof Error ? error.message : 'Unknown error'}`);
        }
    }, []);

    const runAnalysis = useCallback(async () => {
        if (selectedFiles.length === 0) {
            setError("Vui lòng chọn ít nhất một tệp để phân tích.");
            return;
        }
        setIsAnalyzing(true);
        setError(null);
        setSummaryData(null);

        try {
            console.log(`Starting analysis of ${selectedFiles.length} files...`);
            const results = await analyzeDocuments(selectedFiles);
            console.log("Analysis results:", results);
            
            const validResults = results.filter(r => r !== null) as ExtractedInfo[];
            console.log(`Valid results: ${validResults.length}/${results.length}`);
            
            // Chỉ hiển thị thông báo lỗi nếu không có kết quả hợp lệ nào
            if (validResults.length === 0) {
                setError(`❌ Không thể xử lý bất kỳ tệp nào trong số ${selectedFiles.length} tệp đã chọn. Vui lòng kiểm tra:\n• Tệp có phải là ảnh bảng phân công hoặc biên bản chấm báo cáo đồ án không?\n• Chất lượng ảnh có rõ nét không?\n•`);
            } else if (validResults.length < selectedFiles.length) {
                // Chỉ hiển thị cảnh báo nhẹ nếu có ít nhất 1 tệp thành công
                const failedCount = selectedFiles.length - validResults.length;
                const successCount = validResults.length;
                
                console.warn(`⚠️ Chỉ xử lý được ${successCount} trong số ${selectedFiles.length} tệp. ${failedCount} tệp không thể xử lý.`);
                // Không setError để không hiển thị thông báo lỗi khi đã có kết quả thành công
            }
            if (validResults.length > 0) {
                console.log("Aggregating valid results...");
                const aggregated = aggregateData(validResults);
                setSummaryData(aggregated);
                await saveReport(aggregated);
                setSavedReports(prev => [aggregated, ...prev.filter(r => r.id !== aggregated.id)].sort((a,b) => b.id.localeCompare(a.id)));
                console.log("Analysis completed successfully");
                // Xóa thông báo lỗi khi có kết quả thành công
                setError(null);
            } else {
                 setError('❌ Không thể tạo báo cáo từ các tệp đã chọn.\n\n💡 Nguyên nhân có thể:\n• Tệp không phải là ảnh bảng phân công hoặc biên bản chấm báo cáo đồ án\n• Chất lượng ảnh kém (mờ, không rõ nét)\n• Ảnh bị lỗi hoặc không đọc được\n•');
            }
        } catch (e) {
            console.error("Analysis failed with error:", e);
            if (e instanceof Error) {
                setError(`❌ Lỗi hệ thống: ${e.message}\n\n💡 Thử:\n• Kiểm tra kết nối internet\n• Thử lại với ảnh khác\n•`);
            } else {
                setError('❌ Đã xảy ra lỗi không xác định trong quá trình phân tích.\n\n💡 Thử:\n• Làm mới trang và thử lại\n•');
            }
        } finally {
            setIsAnalyzing(false);
        }
    }, [selectedFiles]);

    const handleFileUpload = useCallback(async (newFiles: File[]) => {
        setIsUploading(true);
        setError(null);
        
        const existingNames = new Set(uploadedFiles.map(f => f.name));
        const uniqueNewFiles = newFiles.filter(f => !existingNames.has(f.name));

        if (uniqueNewFiles.length === 0) {
            setIsUploading(false);
            return;
        }

        try {
            await Promise.all(uniqueNewFiles.map(file => saveFile(file)));
            setUploadedFiles(prev => [...prev, ...uniqueNewFiles]);
        } catch (err) {
            console.error("Không thể lưu tệp vào DB", err);
            setError("❌ Không thể lưu tệp vào bộ nhớ.\n\n💡 Thử:\n• Làm mới trang và tải lại\n• Kiểm tra dung lượng bộ nhớ trình duyệt");
        } finally {
            setIsUploading(false);
        }
    }, [uploadedFiles]);
    
    const handleClearAll = async () => {
        if (!window.confirm("Bạn có chắc chắn muốn xóa TẤT CẢ các tệp đã tải lên và các báo cáo đã lưu không? Hành động này không thể hoàn tác.")) return;
        try {
            await Promise.all([clearAllFiles(), clearAllReports()]);
            setSummaryData(null);
            setUploadedFiles([]);
            setSelectedFiles([]);
            setSavedReports([]);
            setError(null);
            localStorage.removeItem('cusc-last-viewed-report');
        } catch (err) {
            console.error(`Không thể xóa bộ nhớ`, err);
            setError("❌ Không thể xóa dữ liệu.\n\n💡 Thử:\n• Làm mới trang và thử lại\n• Xóa dữ liệu thủ công từ cài đặt trình duyệt");
        }
    };

    const handleRemoveFile = async (fileName: string) => {
        try {
            await deleteFile(fileName);
            setUploadedFiles(prev => prev.filter(f => f.name !== fileName));
            setSelectedFiles(prev => prev.filter(f => f.name !== fileName));
        } catch(err) {
            console.error(`Không thể xóa tệp ${fileName}`, err);
            setError(`❌ Không thể xóa tệp "${fileName}".\n\n💡 Thử:\n• Làm mới trang và thử lại\n• Xóa tất cả dữ liệu nếu cần`);
        }
    };

    const handleSelectionChange = (file: File, isSelected: boolean) => {
        setSelectedFiles(prev => isSelected ? [...prev, file] : prev.filter(f => f.name !== file.name));
    };

    const handleLoadReport = (reportId: string) => {
        const reportToLoad = savedReports.find(r => r.id === reportId);
        if (reportToLoad) {
            setSummaryData(reportToLoad);
            setError(null);
        }
    };

    const handleDeleteReport = async (reportId: string) => {
        if (!window.confirm("Bạn có chắc chắn muốn xóa báo cáo này không? Hành động này không thể hoàn tác.")) return;
        try {
            await deleteReport(reportId);
            setSavedReports(prev => prev.filter(r => r.id !== reportId));
            if (summaryData?.id === reportId) {
                setSummaryData(null);
                localStorage.removeItem('cusc-last-viewed-report');
            }
        } catch (err) {
             console.error(`Không thể xóa báo cáo ${reportId}`, err);
             setError("❌ Không thể xóa báo cáo.\n\n💡 Thử:\n• Làm mới trang và thử lại\n• Xóa tất cả dữ liệu nếu cần");
        }
    };
    
    const MainContent = () => {
        if (isAnalyzing) {
            return (
                <div className="flex flex-col items-center justify-center h-full min-h-[300px]">
                    <LoaderIcon className="w-12 h-12 animate-spin text-blue-600" />
                    <p className="mt-4 text-slate-600 font-medium">Đang phân tích tài liệu và tổng hợp kết quả...</p>
                </div>
            );
        }
        if (error) {
            return (
                <div className="flex items-center justify-center h-full min-h-[300px] bg-red-50 border border-red-200 rounded-lg p-4">
                   <p className="text-red-700 font-medium text-center">{error}</p>
                </div>
            );
        }
        if (summaryData) {
            return <SummaryTable data={summaryData} setData={setSummaryData} />;
        }
        
        return (
             <div className="flex flex-col items-center justify-center h-full min-h-[300px] text-center">
                <CheckCircleIcon className="w-16 h-16 text-green-500" />
                 <h3 className="text-lg font-medium text-slate-700 mt-4">Sẵn sàng để bắt đầu</h3>
                <p className="text-sm text-slate-500 max-w-sm">Tải lên tệp, chọn những tệp bạn muốn bao gồm, và nhấp vào "Phân tích" để tạo bản tóm tắt. Các báo cáo đã lưu trước đó có thể được tải từ danh sách bên dưới.</p>
            </div>
        );
    }

    return (
        <div className="bg-slate-50 min-h-screen font-sans text-slate-800">
            <Header />
            <main className="container mx-auto p-4 md:p-8">
                {isDbLoading ? (
                     <div className="flex flex-col items-center justify-center h-64">
                        <LoaderIcon className="w-12 h-12 animate-spin text-blue-600" />
                        <p className="mt-4 text-slate-600 font-medium">Đang tải dữ liệu từ bộ nhớ...</p>
                    </div>
                ) : (
                <div className="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <aside className="lg:col-span-4 no-print space-y-8">
                        <div className="bg-white p-6 rounded-xl shadow-md space-y-6">
                            <div>
                                <h2 className="text-xl font-bold text-slate-700">1. Tải lên & Chọn tệp</h2>
                                <p className="text-sm text-slate-500">Tải lên hoặc chọn các tệp hiện có để phân tích.</p>
                            </div>
                            <FileUpload onFileUpload={handleFileUpload} isLoading={isUploading} />
                            <UploadedFilesList 
                                files={uploadedFiles}
                                selectedFiles={selectedFiles}
                                onSelectionChange={handleSelectionChange}
                                onRemoveFile={handleRemoveFile}
                            />
                            {uploadedFiles.length > 0 && (
                                <div className="pt-4 border-t space-y-2">
                                    <button
                                        onClick={runAnalysis}
                                        disabled={isAnalyzing || selectedFiles.length === 0}
                                        className="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition-colors disabled:bg-slate-400 disabled:cursor-not-allowed flex items-center justify-center"
                                    >
                                        {isAnalyzing ? <LoaderIcon className="w-5 h-5 animate-spin"/> : `Phân tích tệp đã chọn (${selectedFiles.length})`}
                                    </button>
                                    <button
                                        onClick={checkAPI}
                                        className="w-full bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition-colors text-sm"
                                    >
                                        Kiểm tra API Gemini
                                    </button>
                                    {apiStatus && (
                                        <div className="text-sm p-2 bg-blue-50 border border-blue-200 rounded">
                                            {apiStatus}
                                        </div>
                                    )}
                                </div>
                            )}
                        </div>
                        <SavedReportsList reports={savedReports} onLoad={handleLoadReport} onDelete={handleDeleteReport} />
                        <div className="bg-white p-6 rounded-xl shadow-md space-y-4">
                             <h2 className="text-xl font-bold text-slate-700">Tùy chọn</h2>
                             <button
                                onClick={handleClearAll}
                                className="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition-colors text-sm"
                            >
                                Xóa tất cả dữ liệu đã lưu
                            </button>
                        </div>
                    </aside>
                    <div className="lg:col-span-8">
                        <div className="bg-white p-6 rounded-xl shadow-md min-h-[400px]">
                           <h2 className="text-xl font-bold text-slate-700 mb-4 no-print">2. Xem lại & Xuất tóm tắt</h2>
                            <MainContent />
                        </div>
                    </div>
                </div>
                )}
            </main>
        </div>
    );
};

export default App;
