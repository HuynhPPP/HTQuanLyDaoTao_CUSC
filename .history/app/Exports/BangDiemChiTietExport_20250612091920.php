<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;

class BangDiemChiTietExport implements FromCollection, WithHeadings, WithTitle, WithEvents
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
            'Họ Tên', 
            'Điểm Lý Thuyết',
            'Điểm Thực Hành', 
            'Điểm Dự Án',
            'Điểm Trung Bình',
            'Xếp Loại',
            'Ghi Chú'
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

                // Chèn thông tin lớp học và môn học
                $sheet->insertNewRowBefore(1, 10);
                
                $sheet->mergeCells('A1:H1')->setCellValue('A1', 'TRUNG TÂM CÔNG NGHỆ PHẦN MỀM - CUSC');
                $sheet->mergeCells('A2:H2')->setCellValue('A2', 'BẢNG ĐIỂM CHI TIẾT');
                
                $sheet->mergeCells('A4:H4')->setCellValue('A4', "Lớp: {$this->lopHoc->MaLop}");
                $sheet->mergeCells('A5:H5')->setCellValue('A5', "Chương Trình: {$this->chuongTrinh->TenChuongTrinh}");
                $sheet->mergeCells('A6:H6')->setCellValue('A6', "Môn Học: {$this->monHoc->TenMH} ({$this->monHoc->MaMH})");

                // Style tiêu đề
                foreach (range(1, 6) as $row) {
                    $sheet->getStyle("A$row")->getFont()->setBold(true);
                    $sheet->getStyle("A$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                $sheet->getStyle('A2')->getFont()->setSize(14);
            },
        ];
    }
} 