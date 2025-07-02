<?php

namespace App\Exports;

use App\Models\DiemThi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DiemThiExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize, WithDrawings
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

                $sheet->insertNewRowBefore(1, 9); // chèn 9 dòng đầu tiên

                // === Tiêu đề tổ chức ===
                $sheet->mergeCells('B1:F1')->setCellValue('B1', 'TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ');
                $sheet->mergeCells('B2:F2')->setCellValue('B2', 'CANTHO UNIVERSITY SOFTWARE CENTER');
                $sheet->mergeCells('B3:F3')->setCellValue('B3', 'Khu III, Đại học Cần Thơ - 01 Lý Tự Trọng, TP. Cần Thơ');

                // === Tiêu đề chính ===
                $sheet->mergeCells('A5:F5')->setCellValue('A5', 'BẢNG ĐIỂM THI MÔN HỌC');
                $sheet->mergeCells('A6:F6')->setCellValue('A6', 'Môn: ' . $this->tenMH);
                $sheet->mergeCells('A7:F7')->setCellValue('A7', 'Lớp: ' . $this->maLop);
                $sheet->mergeCells('A8:F8')->setCellValue('A8', 'Ngày xuất: ' . now()->format('d/m/Y'));

                // Style
                foreach ([1, 2, 3, 5, 6, 7, 8] as $row) {
                    $sheet->getStyle("A$row:F$row")->getFont()->setBold(true);
                    $sheet->getStyle("A$row:F$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                $sheet->getStyle('A5')->getFont()->setSize(14);
            },

            AfterSheet::class => function (AfterSheet $event) {
                $count = DiemThi::where('MaLop', $this->maLop)->where('TenMH', $this->tenMH)->count();
                $startRow = 9;
                $endRow = $startRow + $count;

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
                $event->sheet->getStyle("A" . ($startRow + 1) . ":F$endRow")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('CUSC Logo');
        $drawing->setDescription('Logo Trung tâm Công nghệ Phần mềm');
        $drawing->setPath(public_path('images/banner_cusc.png')); // logo
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(5);

        return [$drawing];
    }
}
