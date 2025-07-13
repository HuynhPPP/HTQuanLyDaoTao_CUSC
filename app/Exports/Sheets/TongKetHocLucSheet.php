<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\{FromArray, WithTitle, WithHeadings, WithEvents, ShouldAutoSize, WithDrawings};
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class TongKetHocLucSheet implements FromArray, WithTitle, WithHeadings, WithEvents, ShouldAutoSize, WithDrawings
{
    protected $lop, $tongKet;

    public function __construct($lop, $tongKet)
    {
        $this->lop = $lop;
        $this->tongKet = $tongKet;
    }

    public function array(): array
    {
        return $this->tongKet->map(fn($sv) => [
            $sv['MaSV'],
            $sv['HoTen'],
            $sv['DiemTB'],
            $sv['XepLoai'],
        ])->toArray();
    }

    public function headings(): array
    {
        return ['Mã SV', 'Họ tên', 'Điểm TB', 'Xếp loại'];
    }

    public function title(): string
    {
        return 'Tổng kết học lực';
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->insertNewRowBefore(1, 8);

                $sheet->mergeCells('B1:E1')->setCellValue('B1', 'TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ');
                $sheet->mergeCells('B2:E2')->setCellValue('B2', 'CANTHO UNIVERSITY SOFTWARE CENTER');
                $sheet->mergeCells('B3:E3')->setCellValue('B3', 'Khu III, Đại học Cần Thơ - 01 Lý Tự Trọng, TP. Cần Thơ');

                $sheet->mergeCells('A5:E5')->setCellValue('A5', 'TỔNG KẾT HỌC LỰC');
                $sheet->mergeCells('A6:E6')->setCellValue('A6', 'LỚP: ' . $this->lop->MaLop);

                foreach ([1, 2, 3, 5, 6] as $row) {
                    $sheet->getStyle("A$row:E$row")->getFont()->setBold(true);
                    $sheet->getStyle("A$row:E$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                $sheet->getStyle('A5')->getFont()->setSize(14);
            },
            AfterSheet::class => function (AfterSheet $event) {
                $start = 10;
                $end = $start + count($this->tongKet) + 3;

                $sheet = $event->sheet->getDelegate();
                $sheet->getStyle("A$start:E$end")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("A$start:E$end")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
                ]);
                $sheet->getStyle("A$start:E$start")->getFont()->setBold(true);
            },
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('CUSC Logo');
        $drawing->setDescription('Logo CUSC');
        $drawing->setPath(public_path('images/banner_cusc.png'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(5);
        $drawing->setOffsetY(5);
        return [$drawing];
    }
}
