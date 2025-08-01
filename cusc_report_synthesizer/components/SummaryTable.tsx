
import React, { useState, useEffect } from 'react';
import { SummaryData, SummaryEntry, SummaryInstructor } from '../types.ts';
import { PrintIcon, ExportIcon, TrashIcon, PlusCircleIcon } from './Icons.tsx';
import { exportSummaryToExcel } from '../services/excelExportService.ts';

interface SummaryTableProps {
    data: SummaryData;
    setData: React.Dispatch<React.SetStateAction<SummaryData | null>>;
}

const EditableCell: React.FC<{ value: string | number; onChange: (value: string | number) => void; type?: string; className?: string; }> = ({ value, onChange, type = 'text', className = '' }) => {
    const [currentValue, setCurrentValue] = useState(value);

    useEffect(() => {
        setCurrentValue(value);
    }, [value]);

    const handleBlur = () => {
        const finalValue = type === 'number' ? parseFloat(String(currentValue)) || 0 : currentValue;
        if (finalValue !== value) {
            onChange(finalValue);
        }
    };

    const handleKeyDown = (e: React.KeyboardEvent<HTMLInputElement>) => {
        if (e.key === 'Enter') {
            e.currentTarget.blur();
        }
    };

    return (
        <input
            type={type}
            value={currentValue}
            onChange={(e) => setCurrentValue(e.target.value)}
            onBlur={handleBlur}
            onKeyDown={handleKeyDown}
            className={`w-full bg-transparent focus:bg-white focus:ring-1 focus:ring-blue-500 focus:outline-none p-1 rounded ${className}`}
        />
    );
};

// Thêm các hàm tính toán ở đầu file
function calcHoursFromTimeRange(timeRange: string): number {
    const match = timeRange.match(/(\d{1,2}):(\d{2})\s*[–-]\s*(\d{1,2}):(\d{2})/);
    if (!match) return 0;
    const start = parseInt(match[1]) + parseInt(match[2]) / 60;
    const end = parseInt(match[3]) + parseInt(match[4]) / 60;
    return Math.max(0, end - start);
}

function getGroupCount(classInfo: string): number {
    const match = classInfo.match(/(\d+)\s*nhóm/i);
    return match ? parseInt(match[1]) : 1;
}

function getYearType(notes: string): 1 | 2 {
    if (/năm\s*2|học\s*kỳ\s*2/i.test(notes)) return 2;
    return 1;
}

function calcInstructorHours(inst: SummaryInstructor, entry: SummaryEntry): number {
    if (/phản\s*biện/i.test(inst.role)) {
        const groupCount = getGroupCount(entry.classInfo);
        const yearType = getYearType(inst.notes);
        return yearType === 1 ? 1.5 * groupCount : 2.0 * groupCount;
    }
    return calcHoursFromTimeRange(entry.timeRange);
}

export const SummaryTable: React.FC<SummaryTableProps> = ({ data, setData }) => {

    const handleEntryChange = <K extends keyof SummaryEntry, V extends SummaryEntry[K]>(id: string, field: K, value: V) => {
        setData(prevData => {
            if (!prevData) return null;
            return {
                ...prevData,
                entries: prevData.entries.map(entry => entry.id === id ? { ...entry, [field]: value } : entry)
            };
        });
    };

    const handleInstructorChange = <K extends keyof SummaryInstructor, V extends SummaryInstructor[K]>(entryId: string, instructorId: string, field: K, value: V) => {
        setData(prevData => {
            if (!prevData) return null;
            const newEntries = prevData.entries.map(entry => {
                if (entry.id === entryId) {
                    // Chỉ sửa đúng instructor được chọn, không đồng bộ GVHD/GVPB
                    const newInstructors = entry.instructors.map(inst => {
                        if (inst.id === instructorId) {
                            return { ...inst, [field]: value };
                        }
                        return inst;
                    });
                    return { ...entry, instructors: newInstructors };
                }
                return entry;
            });
            return { ...prevData, entries: newEntries };
        });
    };

    const handleFooterChange = <K extends keyof SummaryData, V extends SummaryData[K]>(field: K, value: V) => {
        setData(prevData => {
            if (!prevData) return null;
            return { ...prevData, [field]: value };
        });
    };

    const handleAddInstructor = (entryId: string) => {
        setData(prev => {
            if (!prev) return null;
            const newInstructor: SummaryInstructor = {
                id: `inst-${Date.now()}-${Math.random()}`,
                name: 'Tên giảng viên',
                role: 'Vai trò',
                hours: 0,
                notes: ''
            };
            return {
                ...prev,
                entries: prev.entries.map(entry =>
                    entry.id === entryId
                        ? { ...entry, instructors: [...entry.instructors, newInstructor] }
                        : entry
                )
            };
        });
    };

    const handleDeleteInstructor = (entryId: string, instructorId: string) => {
        setData(prev => {
            if (!prev) return null;
            return {
                ...prev,
                entries: prev.entries.map(entry =>
                    entry.id === entryId
                        ? { ...entry, instructors: entry.instructors.filter(inst => inst.id !== instructorId) }
                        : entry
                )
            };
        });
    };

    const handlePrint = () => {
        window.print();
    };

    const handleExportExcel = () => {
        exportSummaryToExcel(data);
    };

    return (
        <div className="print-container">
            <div className="flex justify-between items-center mb-4 no-print">
                <p className="text-sm text-slate-500">Dữ liệu đã tạo có thể chỉnh sửa và được lưu tự động.</p>
                <div className="flex items-center gap-2">
                    <button
                        onClick={handleExportExcel}
                        className="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition-colors"
                    >
                        <ExportIcon className="w-5 h-5" />
                        Xuất Excel
                    </button>
                    <button
                        onClick={handlePrint}
                        className="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors"
                    >
                        <PrintIcon className="w-5 h-5" />
                        In / PDF
                    </button>
                </div>
            </div>

            <div className="p-4 sm:p-6 border rounded-lg" style={{ fontFamily: '"Times New Roman", Times, serif' }}>
                <div className="text-center mb-6 text-xs">
                    <img src="/Images/banner_cusc.png" alt="CUSC Logo" className="h-20 mx-auto mb-2" />
                    <p className="font-bold uppercase">TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ</p>
                    <p className="font-bold uppercase">CANTHO UNIVERSITY SOFTWARE CENTER</p>
                    <hr className="my-1 border-t border-black mx-auto w-1/3" />
                    <p>Khu III, Đại học Cần Thơ - 01 Lý Tự Trọng, TP. Cần Thơ - Tel: 0292.3731072 & Fax: 0292.3731071</p>
                </div>

                <h1 className="text-center font-bold text-base mb-2">BẢNG THỐNG KÊ CÁC BUỔI CHẤM BÁO CÁO ĐỒ ÁN</h1>
                <div className="text-center font-bold text-sm mb-4">
                    <div className="flex justify-center items-center gap-1">
                        <span>THÁNG</span>
                        <div className="w-16">
                            <EditableCell
                                type="number"
                                value={String(data.month).padStart(2, '0')}
                                onChange={v => handleFooterChange('month', Number(v) || 1)}
                                className="text-center font-bold"
                            />
                        </div>
                        <span>-</span>
                        <div className="w-24">
                            <EditableCell
                                type="number"
                                value={data.year}
                                onChange={v => handleFooterChange('year', Number(v) || new Date().getFullYear())}
                                className="text-center font-bold"
                            />
                        </div>
                    </div>
                </div>

                <div className="my-4 p-2 border text-xs text-slate-700">
                    <p className="font-bold">Ghi chú về cách tính giờ:</p>
                    <ul className="list-disc list-inside ml-2">
                        <li>
                            <span className="font-bold">Giờ chấm đồ án (GV Phản biện):</span> 1.5 giờ * số nhóm (Đối với đồ án Năm 1) hoặc 2.0 giờ * số nhóm (Đối với đồ án Năm 2).
                        </li>
                        <li>
                            <span className="font-bold">Thời gian buổi báo cáo:</span> Giờ bắt đầu tính theo biên bản, giờ kết thúc được điều chỉnh theo quy chế của trung tâm.
                        </li>
                    </ul>
                </div>

                <table className="w-full border-collapse text-xs">
                    <thead className="bg-gray-200">
                        <tr>
                            <th className="border border-slate-600 p-2 font-bold w-[5%]">STT</th>
                            <th className="border border-slate-600 p-2 font-bold w-[25%]">HỌ VÀ TÊN</th>
                            <th className="border border-slate-600 p-2 font-bold w-[30%]">CÔNG VIỆC</th>
                            <th className="border border-slate-600 p-2 font-bold w-[10%]">SỐ GIỜ</th>
                            <th className="border border-slate-600 p-2 font-bold w-[20%]">GHI CHÚ</th>
                            <th className="border border-slate-600 p-2 font-bold w-[20%]">THAO TÁC</th>
                        </tr>
                    </thead>
                    <tbody>
                        {data.entries.map((entry, entryIndex) => {
                            // Với logic mới, chỉ có dòng "Giáo viên phản biện" có ghi chú
                            // Không cần merge cells nữa
                            return (
                                <React.Fragment key={entry.id}>
                                    <tr className="bg-slate-100 font-bold print-break-inside-avoid">
                                        <td colSpan={6} className="border border-slate-500 p-1">
                                            <div className="flex justify-between items-center gap-4 px-2">
                                                <div className="flex items-baseline gap-1">
                                                    <span>Ngày:</span>
                                                    <EditableCell
                                                        value={entry.date}
                                                        onChange={v => handleEntryChange(entry.id, 'date', v as string)}
                                                        className="w-24 font-bold"
                                                    />
                                                    <span>(</span>
                                                    <EditableCell
                                                        value={entry.timeRange}
                                                        onChange={v => handleEntryChange(entry.id, 'timeRange', v as string)}
                                                        className="w-28 font-bold"
                                                    />
                                                    <span>)</span>
                                                </div>
                                                <div className="flex items-baseline flex-grow min-w-0">
                                                    <span className="pr-2">Lớp:</span>
                                                    <EditableCell value={entry.classInfo} onChange={v => handleEntryChange(entry.id, 'classInfo', v as string)} className="font-bold flex-grow" />
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    {entry.instructors.map((inst, instIndex) => (
                                        <tr className="print-break-inside-avoid hover:bg-slate-50 group" key={inst.id}>
                                            <td className="border border-slate-500 p-1 text-center">{inst.stt ?? (instIndex + 1)}</td>
                                            <td className="border border-slate-500 p-0">
                                                <EditableCell value={inst.name} onChange={v => handleInstructorChange(entry.id, inst.id, 'name', v as string)} />
                                            </td>
                                            <td className={`border border-slate-500 p-0${inst.isChamDoAn ? ' font-bold' : ''}`}> 
                                                <EditableCell value={inst.role} onChange={v => handleInstructorChange(entry.id, inst.id, 'role', v as string)} />
                                            </td>
                                            <td className={`border border-slate-500 p-0 text-center${inst.isChamDoAn ? ' font-bold' : ''}`}> 
                                                <EditableCell
                                                    value={
                                                        inst.hours !== undefined && inst.hours !== null && inst.hours !== 0
                                                            ? Number(inst.hours).toFixed(2)
                                                            : calcInstructorHours(inst, entry).toFixed(2)
                                                    }
                                                    onChange={v => handleInstructorChange(entry.id, inst.id, 'hours', Number(v))}
                                                    type="number"
                                                />
                                            </td>
                                            {/* Hiển thị ghi chú cho từng dòng riêng biệt */}
                                            <td className={`border border-slate-500 p-0${inst.isChamDoAn ? ' font-bold' : ''}`}>
                                                <EditableCell
                                                    value={inst.notes || ''}
                                                    onChange={v => handleInstructorChange(entry.id, inst.id, 'notes', v as string)}
                                                />
                                            </td>
                                            <td className="border border-slate-500 p-1 text-center no-print">
                                                <button onClick={() => handleDeleteInstructor(entry.id, inst.id)} className="text-slate-400 hover:text-red-600 opacity-0 group-hover:opacity-100 transition-opacity" title="Xóa giảng viên">
                                                    <TrashIcon className="w-5 h-5"/>
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                    <tr className="no-print">
                                        <td colSpan={6} className="border-x border-b border-slate-500 p-1">
                                            <button
                                                onClick={() => handleAddInstructor(entry.id)}
                                                className="flex items-center justify-center gap-2 w-full text-sm text-blue-600 hover:bg-blue-50 rounded-md py-1"
                                                title="Thêm giảng viên vào phiên này"
                                            >
                                                <PlusCircleIcon className="w-5 h-5" />
                                                Thêm giảng viên
                                            </button>
                                        </td>
                                    </tr>
                                </React.Fragment>
                            );
                        })}
                    </tbody>
                </table>

                <div className="grid grid-cols-2 gap-8 mt-12 text-center print-break-inside-avoid text-sm">
                    <div>
                        <EditableCell
                            value={data.signatureDate}
                            onChange={v => handleFooterChange('signatureDate', v as string)}
                            className="text-center"
                        />
                        <p className="font-bold mt-2">NGƯỜI LẬP</p>
                        <p className="text-xs italic">(Ký, họ tên)</p>
                        <div className="mt-24">
                            <EditableCell value={data.preparer} onChange={v => handleFooterChange('preparer', v as string)} className="text-center font-bold" />
                        </div>
                    </div>
                    <div>
                        <EditableCell
                            value={data.signatureDate}
                            onChange={v => handleFooterChange('signatureDate', v as string)}
                            className="text-center"
                        />
                        <p className="font-bold mt-2">P. TRƯỞNG BP ĐÀO TẠO</p>
                        <p className="text-xs italic">(Ký, họ tên)</p>
                        <div className="mt-24">
                            <EditableCell value={data.approver} onChange={v => handleFooterChange('approver', v as string)} className="text-center font-bold" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};
