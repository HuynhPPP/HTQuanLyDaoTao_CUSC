<?php

namespace App\Exports;

use App\Models\DiemThi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DiemThiExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    protected $maLop;
    protected $tenMH;

    public function __construct($maLop, $tenMH)
    {
        $this->maLop = $maLop;
        $this->tenMH = $tenMH;
    }

    public function collection()
    {
        return DiemThi::where('MaLop', $this->maLop)
                     ->where('TenMH', $this->tenMH)
                     ->get(['MaSV', 'TenMH', 'MaLop', 'LanThi', 'Diem', 'GhiChu']);
    }

    public function headings(): array
    {
        return [
            'Mã SV',
            'Tên Môn Học',
            'Mã Lớp', 
            'Lần Thi',
            'Điểm',
            'Ghi Chú'
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Insert dòng đầu
                $sheet->insertNewRowBefore(1, 5);

                // Tiêu đề
                $sheet->mergeCells('A1:F1')->setCellValue('A1', 'BẢNG ĐIỂM THI MÔN HỌC');
                $sheet->mergeCells('A2:F2')->setCellValue('A2', 'Môn: ' . $this->tenMH);
                $sheet->mergeCells('A3:F3')->setCellValue('A3', 'Lớp: ' . $this->maLop);
                $sheet->mergeCells('A4:F4')->setCellValue('A4', 'Ngày xuất: ' . now()->format('d/m/Y'));

                foreach (range(1, 4) as $row) {
                    $sheet->getStyle("A$row")->getFont()->setBold(true);
                    $sheet->getStyle("A$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                $sheet->getStyle('A1')->getFont()->setSize(14);
            },

            AfterSheet::class => function (AfterSheet $event) {
                $dataRowCount = DiemThi::where('MaLop', $this->maLop)
                                        ->where('TenMH', $this->tenMH)
                                        ->count();
                $startRow = 6;
                $endRow = $startRow + $dataRowCount;

                // Header style
                $event->sheet->getStyle("A$startRow:F$startRow")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'DDDDDD'],
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Border toàn bảng
                $event->sheet->getStyle("A$startRow:F$endRow")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // Căn giữa dữ liệu
                $event->sheet->getStyle("A".($startRow+1).":F$endRow")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
        ];
    }
}
