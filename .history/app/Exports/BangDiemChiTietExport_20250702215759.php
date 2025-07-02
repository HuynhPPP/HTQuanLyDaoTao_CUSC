<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BangDiemChiTietExport implements FromCollection, WithHeadings, WithTitle, WithEvents, ShouldAutoSize, WithDrawings
{
    protected $danhSachDiem;
    protected $lopHoc;
    protected $monHoc;
    protected $chuongTrinh;

    public function __construct($danhSachDiem, $lopHoc, $monHoc, $chuongTrinh)
    {
        $this->danhSachDiem = $danhSachDiem;
        $this->lopHoc = $lopHoc;
        $this->monHoc = $monHoc;
        $this->chuongTrinh = $chuongTrinh;
    }

    public function collection()
    {
        return $this->danhSachDiem->map(function ($diem) {
            return [
                'MaSV' => $diem->MaSV,
                'HoTen' => $diem->HoTen,
                'DiemLyThuyet' => number_format($diem->DiemLyThuyet ?? 0, 2),
                'DiemThucHanh' => number_format($diem->DiemThucHanh ?? 0, 2),
                'DiemDuAn' => number_format($diem->DiemDuAn ?? 0, 2),
                'DiemTong' => number_format($diem->DiemTong, 2),
                'XepLoai' => $diem->XepLoai,
                'GhiChu' => $diem->GhiChu ?? ''
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Mã SV',
            'Họ tên',
            'Điểm lý thuyết',
            'Điểm thực hành',
            'Điểm dự án',
            'Điểm trung bình',
            'Ghi chú'
        ];
    }

    public function title(): string
    {
        return 'Bảng Điểm Chi Tiết';
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->insertNewRowBefore(1, 9); // chèn 9 dòng đầu
    
                // === Tiêu đề tổ chức ===
                $sheet->mergeCells('B1:H1')->setCellValue('B1', 'TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ');
                $sheet->mergeCells('B2:H2')->setCellValue('B2', 'CANTHO UNIVERSITY SOFTWARE CENTER');
                $sheet->mergeCells('B3:H3')->setCellValue('B3', 'Khu III, Đại học Cần Thơ - 01 Lý Tự Trọng, TP. Cần Thơ');

                // === Tiêu đề chính ===
                $sheet->mergeCells('A5:H5')->setCellValue('A5', 'BẢNG ĐIỂM CHI TIẾT');
                $sheet->mergeCells('A6:H6')->setCellValue('A6', "Lớp: {$this->lopHoc->MaLop}");
                $sheet->mergeCells('A7:H7')->setCellValue('A7', "Chương trình: {$this->chuongTrinh->TenChuongTrinh}");
                $sheet->mergeCells('A8:H8')->setCellValue('A8', "Môn học: {$this->monHoc->TenMH} ({$this->monHoc->MaMH})");

                foreach ([1, 2, 3, 5, 6, 7, 8] as $row) {
                    $sheet->getStyle("A$row:H$row")->getFont()->setBold(true);
                    $sheet->getStyle("A$row:H$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                $sheet->getStyle('A5')->getFont()->setSize(14);
            },

            AfterSheet::class => function (AfterSheet $event) {
                $count = $this->danhSachDiem->count();
                $startRow = 9;
                $endRow = $startRow + $count + 1;

                // Header style
                $event->sheet->getStyle("A$startRow:H$startRow")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'DDDDDD'],
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // Border toàn bảng
                $event->sheet->getStyle("A$startRow:H$endRow")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // Căn giữa dữ liệu
                $event->sheet->getStyle("A" . ($startRow + 1) . ":H$endRow")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('CUSC Logo');
        $drawing->setDescription('Logo Trung tâm Công nghệ Phần mềm');
        $drawing->setPath(public_path('images/banner_cusc.png')); // logo CUSC
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(5);

        return [$drawing];
    }
}
