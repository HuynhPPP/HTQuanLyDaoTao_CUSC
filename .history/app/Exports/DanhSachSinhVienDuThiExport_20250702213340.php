<?php

namespace App\Exports;

use App\Models\LichThi;
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

class DanhSachSinhVienDuThiExport implements FromCollection, WithHeadings, WithTitle, WithEvents, WithDrawings, ShouldAutoSize
{
    protected $maLichThi;

    public function __construct($maLichThi)
    {
        $this->maLichThi = $maLichThi;
    }

    public function collection()
    {
        $students = \App\Models\SinhVienDuThi::with(['sinhVien'])
            ->where('MaLichThi', $this->maLichThi)
            ->get();

        return $students->map(function ($item, $index) {
            return [
                'STT' => $index + 1,
                'MaSV' => $item->MaSV,
                'HoTen' => $item->sinhVien->HoTen,
                'TrangThai' => $this->getTrangThaiLabel($item->TrangThaiDuThi),
                'GhiChu' => $item->GhiChu ?? '',
            ];
        });
    }

    public function headings(): array
    {
        return ['STT', 'Mã SV', 'Họ Tên', 'Trạng Thái', 'Ghi Chú'];
    }

    public function title(): string
    {
        return 'Danh Sách Dự Thi';
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lichThi = LichThi::with(['lopHoc', 'monHoc', 'phanCongThi'])->find($this->maLichThi);
                $canBoList = $lichThi->canBos->map(fn($cb) => $cb->canBo->HoTenCB)->values();
            
                $sheet->insertNewRowBefore(1, 9); // chèn dòng header
            
                // Header tổ chức
                $sheet->mergeCells('B1:E1')->setCellValue('B1', 'TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ');
                $sheet->mergeCells('B2:E2')->setCellValue('B2', 'CANTHO UNIVERSITY SOFTWARE CENTER');
                $sheet->mergeCells('B3:E3')->setCellValue('B3', 'Khu III, Đại học Cần Thơ - 01 Lý Tự Trọng, TP. Cần Thơ');
            
                // Tiêu đề chính
                $sheet->mergeCells('A5:E5')->setCellValue('A5', 'DANH SÁCH SINH VIÊN DỰ THI');
                $sheet->mergeCells('A6:E6')->setCellValue('A6', 'Môn: ' . $lichThi->TenMH . ' | Lớp: ' . $lichThi->MaLop);
                $sheet->mergeCells('A7:E7')->setCellValue('A7', 'Ngày thi: ' . \Carbon\Carbon::parse($lichThi->NgayThi)->format('d/m/Y') . ' - Phòng: ' . $lichThi->PhongThi);
                $sheet->mergeCells('A8:E8')->setCellValue('A8', 'Cán bộ coi thi: ' . $canBoList->implode(', '));
            
                // Style header
                foreach ([1,2,3,5,6,7,8] as $row) {
                    $sheet->getStyle("A$row:E$row")->getFont()->setBold(true);
                    $sheet->getStyle("A$row:E$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }
            
                $sheet->getStyle('A5')->getFont()->setSize(14);
            },
            

            AfterSheet::class => function (AfterSheet $event) {
                $count = \App\Models\SinhVienDuThi::where('MaLichThi', $this->maLichThi)->count();
                $startRow = 10;
                $endRow = $startRow + $count;

                // Border toàn bảng
                $event->sheet->getStyle("A$startRow:E$endRow")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                // Canh giữa nội dung
                $event->sheet->getStyle("A$startRow:E$endRow")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Header bảng
                $event->sheet->getStyle("A$startRow")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'CCCCCC'],
                    ],
                ]);
            },
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('CUSC Logo');
        $drawing->setDescription('Logo Trung tâm Công nghệ Phần mềm');
        $drawing->setPath(public_path('images/banner_cusc.png'));
        $drawing->setHeight(80);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(5);

        return [$drawing];
    }

    private function getTrangThaiLabel($trangThai)
    {
        return [
            'DangKy' => 'Đăng Ký',
            'DuThi' => 'Dự Thi',
            'VangMat' => 'Vắng Mặt',
            'KhongDuThi' => 'Không Dự Thi',
            'ChuaDangKy' => 'Chưa Đăng Ký',
        ][$trangThai] ?? $trangThai;
    }
}
