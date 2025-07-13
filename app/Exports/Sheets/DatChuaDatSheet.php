<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\{
    FromArray, WithTitle, WithHeadings, WithEvents, ShouldAutoSize, WithDrawings
};
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DatChuaDatSheet implements FromArray, WithTitle, WithHeadings, WithEvents, ShouldAutoSize, WithDrawings
{
    protected $lop, $thongKeDat;

    public function __construct($lop, $thongKeDat)
    {
        $this->lop = $lop;
        $this->thongKeDat = $thongKeDat;
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->thongKeDat as $maMH => $stats) {
            $tenMH = \App\Models\MonHoc::find($maMH)->TenMH ?? $maMH;
            $tyLe = $stats['tong'] > 0 ? round(($stats['dat'] / $stats['tong']) * 100, 1) : 0;

            $rows[] = [
                $tenMH,
                $stats['dat'],
                $stats['khongDat'],
                $stats['tong'],
                $tyLe . '%'
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Môn học', 'Đạt', 'Không đạt', 'Tổng', 'Tỷ lệ đạt'];
    }

    public function title(): string
    {
        return 'Đạt / Chưa đạt';
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->insertNewRowBefore(1, 9);

                $sheet->mergeCells('B1:E1')->setCellValue('B1', 'TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ');
                $sheet->mergeCells('B2:E2')->setCellValue('B2', 'CANTHO UNIVERSITY SOFTWARE CENTER');
                $sheet->mergeCells('B3:E3')->setCellValue('B3', 'Khu III, Đại học Cần Thơ - 01 Lý Tự Trọng, TP. Cần Thơ');

                $sheet->mergeCells('A5:E5')->setCellValue('A5', 'THỐNG KÊ ĐẠT/CHƯA ĐẠT');
                $sheet->mergeCells('A6:E6')->setCellValue('A6', 'LỚP: ' . $this->lop->MaLop);

                foreach ([1, 2, 3, 5, 6] as $row) {
                    $sheet->getStyle("A$row:E$row")->getFont()->setBold(true);
                    $sheet->getStyle("A$row:E$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                $sheet->getStyle('A5')->getFont()->setSize(14);
            },

            AfterSheet::class => function (AfterSheet $event) {
                $start = 10;
                $end = $start + count($this->thongKeDat) + 3;

                $sheet = $event->sheet->getDelegate();

                $sheet->getStyle("A$start:E$end")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("A$start:E$start")->getFont()->setBold(true);

                $sheet->getStyle("A$start:E$end")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);
            }
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('CUSC Logo');
        $drawing->setDescription('Logo Trung tâm CNTT');
        $drawing->setPath(public_path('images/banner_cusc.png'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(4);
        $drawing->setOffsetY(4);
        return [$drawing];
    }
}



