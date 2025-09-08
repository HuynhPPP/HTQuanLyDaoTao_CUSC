
import React, { useState, useCallback, useEffect } from 'react';
import { FileUpload } from './components/FileUpload.tsx';
import { SummaryTable } from './components/SummaryTable.tsx';
import { SavedReportsList } from './components/SavedReportsList.tsx';
import { analyzeDocuments, checkGeminiAPI } from './services/geminiService.ts';
import { ExtractedInfo, SummaryData, SummaryInstructor, Instructor } from './types.ts';
import { Header } from './components/Header.tsx';
import { UploadedFilesList } from './components/UploadedFilesList.tsx';
import { LoaderIcon, CheckCircleIcon, DatabaseIcon } from './components/Icons.tsx';
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
    
    return diff / (1000 * 60 * 60); // convert milliseconds to hours
};

/**
 * Finds the most relevant note for grading calculations within a session.
 * It prioritizes notes containing "Năm 1" or "Năm 2" as these are
 * critical for the grading hours formula.
 * @param instructors The list of instructors from an extracted session.
 * @returns The relevant note string or an empty string if none found.
 */
const findSessionGradingNote = (instructors: Instructor[]): string => {
    // Prefer notes that explicitly mention "Năm 1" or "Năm 2" as these are critical for calculation.
    // The Gemini prompt instructs the model to use this format.
    const gradingNote = instructors.find(inst => 
        inst.note?.includes('Năm 1') || 
        inst.note?.includes('Năm 2')
    )?.note;
    
    if (gradingNote) {
        return gradingNote;
    }
    
    // Fallback to the first non-empty note if the specific one isn't found.
    return instructors.find(inst => inst.note && inst.note.trim() !== '')?.note || '';
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
                setError("Không thể tải dữ liệu đã lưu từ bộ nhớ cục bộ của trình duyệt.");
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
        
        const classEntries = validInfos.map((info, sessionIndex) => {
            console.log(`Processing session ${sessionIndex + 1}:`, info);
            
            // Cải thiện parsing số nhóm - xử lý nhiều format
            let groupCountNum = 1;
            if (info.groupCount) {
                const groupCountMatch = info.groupCount.match(/(\d+)\s*nhóm/);
                if (groupCountMatch) {
                    groupCountNum = parseInt(groupCountMatch[1], 10);
                } else {
                    // Thử parse số đơn giản
                    const simpleMatch = info.groupCount.match(/(\d+)/);
                    if (simpleMatch) {
                        groupCountNum = parseInt(simpleMatch[1], 10);
                    }
                }
            }
            
            const sessionDuration = calculateHoursFromTimeRange(info.time);
            
            console.log(`Session ${sessionIndex + 1} details:`, {
                classId: info.classId,
                groupCount: groupCountNum,
                sessionDuration: sessionDuration,
                instructors: info.instructors.length
            });
            
            // Hàm xác định năm học từ thông tin lớp, instructors và semester
            const determineYearLevel = (classId: string, instructors: Instructor[], semester?: string): string => {
                // Ưu tiên sử dụng thông tin semester
                if (semester) {
                    if (/2/.test(semester)) return 'Năm 2';
                    if (/1/.test(semester)) return 'Năm 1';
                }
                // Tìm ghi chú có chứa thông tin năm học
                const normalizeSemesterText = (text: string): string => {
                    // Chuẩn hóa các biến thể I/1, II/2, HK I/II
                    let t = text;
                    t = t.replace(/HK\s*I\b|Học\s*kỳ\s*I\b/gi, 'Học kỳ 1');
                    t = t.replace(/HK\s*II\b|Học\s*kỳ\s*II\b/gi, 'Học kỳ 2');
                    t = t.replace(/Học\s*kỳ\s*0?1\b/gi, 'Học kỳ 1');
                    t = t.replace(/Học\s*kỳ\s*0?2\b/gi, 'Học kỳ 2');
                    return t;
                };

                const yearNote = instructors.find(inst => {
                    const note = inst.note ? normalizeSemesterText(inst.note) : '';
                    return note.includes('Năm 1') || note.includes('Năm 2') || note.includes('Học kỳ 1') || note.includes('Học kỳ 2') || note.includes('Year 1') || note.includes('Year 2');
                })?.note;
                if (yearNote) {
                    const nn = normalizeSemesterText(yearNote);
                    if (nn.includes('Năm 2') || nn.includes('Học kỳ 2') || nn.includes('Year 2')) {
                        return 'Năm 2';
                    }
                    if (nn.includes('Năm 1') || nn.includes('Học kỳ 1') || nn.includes('Year 1')) {
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
            console.log(`Session ${sessionIndex + 1} year level:`, yearLevel, `(semester: ${info.semester})`);
            
            // Kiểm tra xem đã có vai trò "Chấm đồ án" trong dữ liệu chưa
            const hasChamDoAn = info.instructors.some(instructor => 
                /chấm\s*đồ\s*án/i.test(instructor.role)
            );
            
            console.log(`Session ${sessionIndex + 1} already has "Chấm đồ án":`, hasChamDoAn);
            
            // Sử dụng dữ liệu chính xác từ Gemini
            const allInstructors: SummaryInstructor[] = [];
            
            info.instructors.forEach((instructor, index) => {
                console.log(`Processing instructor ${index + 1}:`, instructor);
                
                // Validate instructor data
                if (!instructor.name || !instructor.role) {
                    console.warn("Skipping instructor with missing name or role:", instructor);
                    return;
                }
                
                // Tạo ID duy nhất cho mỗi instructor
                const instructorId = `inst-${Date.now()}-${Math.random()}-${index}`;
                
                // Sử dụng số giờ chính xác từ dữ liệu gốc
                let hours = instructor.hours || 0;
                
                // Nếu không có số giờ từ dữ liệu, tính toán theo vai trò
                if (hours === 0) {
                    if (/hướng\s*dẫn|hướng\s*dẫn/i.test(instructor.role)) {
                        // GVHD: số giờ = thời gian buổi báo cáo
                        hours = sessionDuration;
                    } else if (/phản\s*biện/i.test(instructor.role)) {
                        // GVPB: số giờ = thời gian buổi báo cáo (có thể khác GVHD)
                        hours = sessionDuration;
                    } else if (/chấm\s*đồ\s*án/i.test(instructor.role)) {
                        // Chấm đồ án: tính theo công thức
                        let chamDoAnHours = 1.5; // Mặc định cho Năm 1
                        if (yearLevel === 'Năm 2') {
                            chamDoAnHours = 2.0;
                        } else if (yearLevel === 'Năm 3') {
                            chamDoAnHours = 2.5;
                        }
                        hours = chamDoAnHours * groupCountNum;
                    }
                }
                
                // Xử lý ghi chú - cải thiện để linh hoạt hơn
                let note = instructor.note || '';
                // Chuẩn hóa các biến thể học kỳ I/II -> 1/2
                note = note.replace(/HK\s*I\b|Học\s*kỳ\s*I\b/gi, 'Học kỳ 1');
                note = note.replace(/HK\s*II\b|Học\s*kỳ\s*II\b/gi, 'Học kỳ 2');
                if (/học\s*kỳ\s*1/i.test(note)) note = 'Năm 1';
                if (/học\s*kỳ\s*2/i.test(note)) note = 'Năm 2';
                if (/học\s*kỳ\s*3/i.test(note)) note = 'Năm 3';
                
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
                console.log(`Session ${sessionIndex + 1} already has "Chấm đồ án" entries, skipping creation`);
            }
            
            console.log(`Session ${sessionIndex + 1} final instructors:`, allInstructors);
            
            // Chuẩn hóa hiển thị số nhóm: luôn là 2 chữ số + " Nhóm"
            const groupCountDisplay = `${String(groupCountNum).padStart(2, '0')} Nhóm`;

            return {
                id: `entry-${Date.now()}-${Math.random()}`,
                date: info.date,
                timeRange: info.time,
                classInfo: `${info.classId} (${groupCountDisplay})-Lần ${info.reportSession}`,
                instructors: allInstructors
            };
        });
        
        // Xác định tháng mới nhất dựa trên ngày của các phiên hợp lệ
        const parseVietnamDate = (dateStr: string): number => {
            const m = dateStr.match(/(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{2,4})/);
            if (!m) return -Infinity;
            const day = parseInt(m[1], 10);
            const month = parseInt(m[2], 10) - 1;
            let year = parseInt(m[3], 10);
            if (year < 100) year += 2000;
            return new Date(year, month, day).getTime();
        };

        const newestDateMs = validInfos
            .map(i => parseVietnamDate(i.date))
            .reduce((max, v) => (v > max ? v : max), -Infinity);
        const newestDate = isFinite(newestDateMs) ? new Date(newestDateMs) : new Date();

        const result = {
            id: `summary-${Date.now()}`,
            entries: classEntries,
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
            
            if(validResults.length < selectedFiles.length) {
                const failedCount = selectedFiles.length - validResults.length;
                setError(`Không thể xử lý ${failedCount} trong số ${selectedFiles.length} tệp đã chọn. ${validResults.length} tệp đã thành công. Vui lòng kiểm tra console để biết chi tiết.`);
            }
            if (validResults.length > 0) {
                console.log("Aggregating valid results...");
                const aggregated = aggregateData(validResults);
                setSummaryData(aggregated);
                await saveReport(aggregated);
                setSavedReports(prev => [aggregated, ...prev.filter(r => r.id !== aggregated.id)].sort((a,b) => b.id.localeCompare(a.id)));
                console.log("Analysis completed successfully");
            } else {
                 setError('Phân tích không mang lại dữ liệu hợp lệ nào từ các tệp đã chọn. Vui lòng kiểm tra console để biết chi tiết lỗi.');
            }
        } catch (e) {
            console.error("Analysis failed with error:", e);
            if (e instanceof Error) {
                setError(`Đã xảy ra lỗi trong quá trình phân tích tệp: ${e.message}`);
            } else {
                setError('Đã xảy ra lỗi trong quá trình phân tích tệp. Vui lòng kiểm tra console để biết chi tiết.');
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
            setError("Không thể lưu các tệp đã tải lên vào bộ nhớ cục bộ.");
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
            setError("Không thể xóa tất cả dữ liệu được lưu trữ.");
        }
    };

    const handleRemoveFile = async (fileName: string) => {
        try {
            await deleteFile(fileName);
            setUploadedFiles(prev => prev.filter(f => f.name !== fileName));
            setSelectedFiles(prev => prev.filter(f => f.name !== fileName));
        } catch(err) {
            console.error(`Không thể xóa tệp ${fileName}`, err);
            setError("Không thể xóa tệp khỏi bộ nhớ.");
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
             setError("Không thể xóa báo cáo khỏi bộ nhớ.");
        }
    };
    
    const MainContent = () => {
        if (isAnalyzing) {
            return (
                <div className="flex flex-col items-center justify-center h-full min-h-[300px]">
                    <LoaderIcon className="w-12 h-12 animate-spin text-blue-600" />
                    <p className="mt-4 text-slate-600 font-medium">Đang phân tích tài liệu với Gemini...</p>
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
