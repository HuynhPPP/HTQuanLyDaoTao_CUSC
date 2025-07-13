<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithTitle,
    WithEvents,
    ShouldAutoSize,
    WithDrawings
};
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DiemMonHocSheet implements FromCollection, WithTitle, WithEvents, ShouldAutoSize, WithDrawings
{
    protected $lop, $theoMon;

    public function __construct($lop, $theoMon)
    {
        $this->lop = $lop;
        $this->theoMon = $theoMon;
    }

    public function collection()
    {
        $rows = collect();

        foreach ($this->theoMon as $maMH => $diems) {
            $tenMH = \App\Models\MonHoc::find($maMH)->TenMH ?? $maMH;
            $rows->push([$tenMH]);
            $rows->push(['Mã SV', 'Họ tên', 'Lý thuyết', 'Thực hành', 'Dự án', 'Tổng điểm', 'Ghi chú']);

            foreach ($diems as $diem) {
                $rows->push([
                    $diem->MaSV,
                    $diem->sinhVien->HoTen ?? '',
                    $diem->DiemLyThuyet,
                    $diem->DiemThucHanh,
                    $diem->DiemDuAn,
                    $diem->DiemTong,
                    $diem->GhiChu,
                ]);
            }

            $rows->push([]);
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Bảng điểm';
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->insertNewRowBefore(1, 8);

                $sheet->mergeCells('B1:G1')->setCellValue('B1', 'TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ');
                $sheet->mergeCells('B2:G2')->setCellValue('B2', 'CANTHO UNIVERSITY SOFTWARE CENTER');
                $sheet->mergeCells('B3:G3')->setCellValue('B3', 'Khu III, Đại học Cần Thơ - 01 Lý Tự Trọng, TP. Cần Thơ');

                $sheet->mergeCells('A5:G5')->setCellValue('A5', 'BẢNG ĐIỂM TỪNG MÔN');
                $sheet->mergeCells('A6:G6')->setCellValue('A6', 'LỚP: ' . $this->lop->MaLop);

                foreach ([1, 2, 3, 5, 6] as $row) {
                    $sheet->getStyle("A$row:G$row")->getFont()->setBold(true);
                    $sheet->getStyle("A$row:G$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                $sheet->getStyle('A5')->getFont()->setSize(14);
            },

            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle("A10:G$lastRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle("A10:G$lastRow")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);
            },
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('CUSC Logo');
        $drawing->setDescription('Logo Trung tâm CNTT');
        $drawing->setPath(public_path('images/banner_cusc.png'));
        $drawing->setHeight(40);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(4);
        $drawing->setOffsetY(4);
        return [$drawing];
    }
}
