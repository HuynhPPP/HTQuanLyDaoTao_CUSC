
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


    const aggregateData = (infos: ExtractedInfo[]): SummaryData => {
        const classEntries = infos.map(info => {
            const groupCountNum = parseInt(info.groupCount.match(/\d+/)?.[0] || '1', 10);
            const sessionDuration = calculateHoursFromTimeRange(info.time);
            let note = info.instructors.find(i => /phản\s*biện/i.test(i.role))?.note || '';
            // Gán ghi chú là Năm 1 hoặc Năm 2 dựa vào học kỳ
            if (/học\s*kỳ\s*1/i.test(note)) note = 'Năm 1';
            if (/học\s*kỳ\s*2/i.test(note)) note = 'Năm 2';
            // Nếu không có ghi chú, thử lấy từ info hoặc mặc định Năm 1
            if (!note) {
                if ('semester' in info && /học\s*kỳ\s*2/i.test((info as any).semester || '')) note = 'Năm 2';
                else note = 'Năm 1';
            }
            const gvhd = info.instructors.find(i => /hướng dẫn/i.test(i.role));
            const gvpb = info.instructors.find(i => /phản\s*biện/i.test(i.role));
            const allInstructors: SummaryInstructor[] = [];
            if (gvhd) {
                allInstructors.push({
                    id: `inst-${Date.now()}-${Math.random()}-hd`,
                    name: gvhd.name,
                    role: gvhd.role,
                    hours: sessionDuration,
                    notes: '', // GVHD không có ghi chú
                });
            }
            if (gvpb) {
                // Số giờ của GVPB = số giờ của GVHD (sessionDuration)
                allInstructors.push({
                    id: `inst-${Date.now()}-${Math.random()}-pb`,
                    name: gvpb.name,
                    role: gvpb.role,
                    hours: sessionDuration,
                    notes: note, // Chỉ GVPB có ghi chú
                });
                allInstructors.push({
                    id: `inst-${Date.now()}-${Math.random()}-chamdoan`,
                    name: gvpb.name,
                    role: 'Chấm đồ án',
                    hours: (/năm\s*2/i.test(note) ? 2.0 : 1.5) * groupCountNum,
                    notes: '', // Chấm đồ án không có ghi chú
                    isChamDoAn: true
                });
            }
            return {
                id: `entry-${Date.now()}-${Math.random()}`,
                date: info.date,
                timeRange: info.time,
                classInfo: `${info.classId} (${info.groupCount})-Lần ${info.reportSession}`,
                instructors: allInstructors
            };
        });
        return {
            id: `summary-${Date.now()}`,
            entries: classEntries,
            month: new Date().getMonth() + 1,
            year: new Date().getFullYear(),
            signatureDate: '',
            preparer: '',
            approver: ''
        };
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
