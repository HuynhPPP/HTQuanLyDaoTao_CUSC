<?php
namespace App\Exports;
use App\Models\Report;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class ReportsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Report::with(['class', 'instructor', 'reviewer'])->get()->map(function($report) {
            return [
                'Lớp' => $report->class->name ?? 'N/A',
                'Đồ án' => $report->report_name,
                'Giáo viên hướng dẫn' => $report->instructor->full_name ?? 'N/A',
                'Giáo viên phản biện' => $report->reviewer->full_name ?? 'N/A',
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