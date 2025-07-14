
import React, { useState, useCallback, useEffect } from 'react';
import { FileUpload } from './components/FileUpload.tsx';
import { SummaryTable } from './components/SummaryTable.tsx';
import { SavedReportsList } from './components/SavedReportsList.tsx';
import { analyzeDocuments } from './services/geminiService.ts';
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


    const aggregateData = (infos: ExtractedInfo[]): SummaryData => {
        const classEntries = infos.map(info => {
            const groupCountNum = parseInt(info.groupCount.match(/\d+/)?.[0] || '1', 10);
            const sessionDuration = calculateHoursFromTimeRange(info.time);
            const sessionNote = findSessionGradingNote(info.instructors);
    
            const allInstructors: SummaryInstructor[] = [];
            const processedGraderNames = new Set<string>();

            info.instructors.forEach((inst, index) => {
                const roleLower = inst.role.toLowerCase();

                // Add the primary role entry (Advisor or Reviewer) with hours based on session duration
                if (roleLower.includes('hướng dẫn') || roleLower.includes('phản biện')) {
                    allInstructors.push({
                        id: `inst-${Date.now()}-${Math.random()}-${index}-primary`,
                        name: inst.name,
                        role: inst.role,
                        hours: sessionDuration,
                        notes: inst.note || '',
                    });
                }
    
                // If the instructor is a Reviewer, automatically add a Grader row with formula-based hours
                if (roleLower.includes('phản biện')) {
                    let graderHours = 0;
                    if (sessionNote.includes('1')) {
                        graderHours = 1.5 * groupCountNum;
                    } else if (sessionNote.includes('2')) {
                        graderHours = 2.0 * groupCountNum;
                    }
    
                    if (graderHours > 0) {
                        allInstructors.push({
                            id: `inst-${Date.now()}-${Math.random()}-${index}-grader`,
                            name: inst.name,
                            role: 'Chấm đồ án',
                            hours: graderHours,
                            notes: '', // Synthetic row has no specific notes
                        });
                        processedGraderNames.add(inst.name);
                    }
                }

                // Fallback: In case the AI returns a "Chấm đồ án" role directly for someone not listed as a reviewer
                if (roleLower.includes('chấm đồ án') && !processedGraderNames.has(inst.name)) {
                     let graderHours = 0;
                    if (sessionNote.includes('1')) {
                        graderHours = 1.5 * groupCountNum;
                    } else if (sessionNote.includes('2')) {
                        graderHours = 2.0 * groupCountNum;
                    }
                     allInstructors.push({
                        id: `inst-${Date.now()}-${Math.random()}-${index}-grader-only`,
                        name: inst.name,
                        role: 'Chấm đồ án',
                        hours: graderHours > 0 ? graderHours : (inst.hours || 0),
                        notes: inst.note || '',
                    });
                }
            });
            
            return {
                id: `session-${Date.now()}-${Math.random()}`,
                date: info.date,
                timeRange: info.time,
                classInfo: `${info.classId} (${info.groupCount})-Lần ${info.reportSession}`,
                instructors: allInstructors
            };
        }).sort((a, b) => {
            const dateA = new Date(a.date.split('/').reverse().join('-'));
            const dateB = new Date(b.date.split('/').reverse().join('-'));
            if (dateA.getTime() !== dateB.getTime()) return dateA.getTime() - dateB.getTime();
            return a.timeRange.localeCompare(b.timeRange);
        });
    
        const now = new Date();
        const firstDateStr = infos[0]?.date;
        let reportMonth = now.getMonth() + 1;
        let reportYear = now.getFullYear();
    
        if (firstDateStr) {
            const dateParts = firstDateStr.split('/');
            if (dateParts.length === 3) {
                reportMonth = parseInt(dateParts[1], 10);
                reportYear = parseInt(dateParts[2], 10);
            }
        }
        
        return {
            id: `report-${Date.now()}`,
            month: reportMonth,
            year: reportYear,
            entries: classEntries,
            preparer: 'Lâm Thị Hồng Nghi',
            approver: 'Cù Vĩnh Lộc',
            signatureDate: `Ngày ${String(now.getDate()).padStart(2, '0')} tháng ${String(now.getMonth() + 1).padStart(2, '0')} năm ${now.getFullYear()}`,
        };
    };
    
    const runAnalysis = useCallback(async () => {
        if (selectedFiles.length === 0) {
            setError("Vui lòng chọn ít nhất một tệp để phân tích.");
            return;
        }
        setIsAnalyzing(true);
        setError(null);
        setSummaryData(null);

        try {
            const results = await analyzeDocuments(selectedFiles);
            const validResults = results.filter(r => r !== null) as ExtractedInfo[];
            if(validResults.length < selectedFiles.length) {
                setError(`Không thể xử lý tất cả các tệp đã chọn. ${validResults.length} trong số ${selectedFiles.length} đã thành công.`);
            }
            if (validResults.length > 0) {
                const aggregated = aggregateData(validResults);
                setSummaryData(aggregated);
                await saveReport(aggregated);
                setSavedReports(prev => [aggregated, ...prev.filter(r => r.id !== aggregated.id)].sort((a,b) => b.id.localeCompare(a.id)));
            } else {
                 setError('Phân tích không mang lại dữ liệu hợp lệ nào từ các tệp đã chọn.');
            }
        } catch (e) {
            console.error(e);
            setError('Đã xảy ra lỗi trong quá trình phân tích tệp. Vui lòng kiểm tra console để biết chi tiết.');
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
                                <div className="pt-4 border-t">
                                     <button
                                        onClick={runAnalysis}
                                        disabled={isAnalyzing || selectedFiles.length === 0}
                                        className="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition-colors disabled:bg-slate-400 disabled:cursor-not-allowed flex items-center justify-center"
                                    >
                                        {isAnalyzing ? <LoaderIcon className="w-5 h-5 animate-spin"/> : `Phân tích tệp đã chọn (${selectedFiles.length})`}
                                    </button>
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
