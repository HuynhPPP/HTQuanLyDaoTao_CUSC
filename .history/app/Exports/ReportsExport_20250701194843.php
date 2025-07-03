<?php

namespace App\Exports;

use App\Models\ThongKeBaoCaoDoAn; // Sử dụng đúng model
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return ThongKeBaoCaoDoAn::with(['class', 'instructor', 'reviewer'])->get()->map(function($report) {
            return [
                'Lớp' => $report->class->TenLop ?? 'N/A', // Truy cập TenLop từ model lophoc
                'Đồ án' => $report->report_name,
                'Giáo viên hướng dẫn' => $report->instructor->HoTenGV ?? 'N/A', // Truy cập HoTenGV từ model giaovien
                'Giáo viên phản biện' => $report->reviewer->HoTenGV ?? 'N/A', // Truy cập HoTenGV từ model giaovien
                'Ngày' => \Carbon\Carbon::parse($report->report_date)->format('d/m/Y'),
                'Giờ bắt đầu' => $report->report_time_start,
                'Giờ kết thúc' => $report->report_time_end,
                'Địa điểm' => $report->location,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Lớp',
            'Đồ án',
            'Giáo viên hướng dẫn',
            'Giáo viên phản biện',
            'Ngày',
            'Giờ bắt đầu',
            'Giờ kết thúc',
            'Địa điểm',
        ];
    }
}