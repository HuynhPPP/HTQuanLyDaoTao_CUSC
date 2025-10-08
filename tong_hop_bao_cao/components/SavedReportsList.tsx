
import React, { useState } from 'react';
import { SummaryData } from '../types.ts';
import { SearchIcon, LoadIcon, TrashIcon, DatabaseIcon } from './Icons.tsx';

interface SavedReportsListProps {
    reports: SummaryData[];
    onLoad: (reportId: string) => void;
    onDelete: (reportId: string) => void;
}

export const SavedReportsList: React.FC<SavedReportsListProps> = ({ reports, onLoad, onDelete }) => {
    const [searchTerm, setSearchTerm] = useState('');

    const filteredReports = reports.filter(report => {
        const reportTitle = `Tháng ${String(report.month).padStart(2, '0')}-${report.year}`;
        const searchLower = searchTerm.toLowerCase();
        return reportTitle.toLowerCase().includes(searchLower) ||
               report.preparer.toLowerCase().includes(searchLower) ||
               report.approver.toLowerCase().includes(searchLower);
    });

    return (
        <div className="bg-white p-6 rounded-xl shadow-md space-y-4">
             <div>
                <h2 className="text-xl font-bold text-slate-700 flex items-center gap-2">
                    <DatabaseIcon className="w-6 h-6 text-slate-600" />
                    Báo cáo đã lưu
                </h2>
                <p className="text-sm text-slate-500">Tải hoặc xóa các bản tóm tắt đã lưu trước đó.</p>
            </div>
            
            {reports.length === 0 ? (
                 <div className="text-center py-8 text-sm text-slate-500 border-t mt-4">
                    <p>Chưa có báo cáo nào được lưu.</p>
                    <p className="text-xs">Lưu một bản tóm tắt sau khi phân tích để tìm nó ở đây.</p>
                </div>
            ) : (
                <>
                    <div className="relative">
                        <SearchIcon className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
                        <input
                            type="text"
                            placeholder="Tìm báo cáo theo tháng, năm..."
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                            className="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        />
                    </div>
                    <ul className="space-y-2 max-h-60 overflow-y-auto pr-1 border-t pt-2">
                        {filteredReports.length > 0 ? filteredReports.map(report => (
                            <li key={report.id} className="flex items-center justify-between bg-slate-50 p-3 rounded-md hover:bg-slate-100 group">
                                <div className="flex-1">
                                    <p className="text-sm font-medium text-slate-800">
                                        Tháng {String(report.month).padStart(2, '0')}-{report.year}
                                    </p>
                                    <p className="text-xs text-slate-500">
                                        Tạo ngày: {new Date(parseInt(report.id.split('-')[1])).toLocaleDateString('vi-VN')}
                                    </p>
                                    <p className="text-xs text-slate-400">
                                        {report.entries.length} buổi báo cáo • {report.entries.reduce((total, entry) => total + entry.instructors.length, 0)} giảng viên
                                    </p>
                                </div>
                                <div className="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button 
                                        onClick={() => onLoad(report.id)} 
                                        className="text-blue-500 hover:text-blue-700 p-1 rounded-full hover:bg-blue-100"
                                        title="Tải báo cáo này"
                                    >
                                        <LoadIcon className="w-5 h-5"/>
                                    </button>
                                    <button 
                                        onClick={() => onDelete(report.id)} 
                                        className="text-slate-400 hover:text-red-500 p-1 rounded-full hover:bg-red-100"
                                        title="Xóa báo cáo này"
                                    >
                                        <TrashIcon className="w-5 h-5"/>
                                    </button>
                                </div>
                            </li>
                        )) : (
                            <div className="text-center py-4 text-sm text-slate-500">
                                Không có báo cáo nào khớp với tìm kiếm của bạn.
                            </div>
                        )}
                    </ul>
                </>
            )}
        </div>
    );
};
