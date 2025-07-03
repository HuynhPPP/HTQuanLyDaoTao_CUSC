<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportSummaryExport implements FromArray, WithHeadings, WithStyles
{
    protected $summary;

    public function __construct(array $summary)
    {
        $this->summary = $summary;
    }

    public function headings(): array
    {
        return ['STT', 'HỌ VÀ TÊN', 'CÔNG VIỆC', 'SỐ GIỜ', 'GHI CHÚ'];
    }

    public function array(): array
    {
        $rows = [];
        $stt = 1;

        foreach ($this->summary as $entry) {
            $nameDisplayed = false;
            $name = $entry['name'];
            $semesterNote = 'Năm ' . $entry['semester'];

            // Giáo viên hướng dẫn
            if ($entry['huong_dan'] > 0) {
                $rows[] = [
                    $stt++,
                    $name,
                    'Giáo viên hướng dẫn',
                    $entry['huong_dan'],
                    $semesterNote
                ];
                $nameDisplayed = true;
            }

            // Giáo viên phản biện
            if ($entry['phan_bien'] > 0) {
                $rows[] = [
                    $stt++,
                    $nameDisplayed ? '' : $name,
                    'Giáo viên phản biện',
                    $entry['phan_bien'],
                    $semesterNote
                ];
                $nameDisplayed = true;
            }

            // Dòng chấm đồ án (luôn có)
            $rows[] = [
                $stt++,
                $nameDisplayed ? '' : $name,
                'Chấm đồ án',
                $entry['tong_cham'],
                ''
            ];
        }

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Định dạng dòng tiêu đề
            1 => ['font' => ['bold' => true]],
        ];
    }
}
