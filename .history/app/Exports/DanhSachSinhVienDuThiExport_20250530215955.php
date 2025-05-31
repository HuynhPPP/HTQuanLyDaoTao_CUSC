<?php

namespace App\Exports;

use App\Models\SinhVienDuThi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DanhSachSinhVienDuThiExport implements FromCollection, WithHeadings, WithTitle, WithEvents, WithColumnFormatting, WithStyles
{
    protected $maLichThi;

    public function __construct($maLichThi)
    {
        $this->maLichThi = $maLichThi;
    }

    public function collection()
    {
        return SinhVienDuThi::with(['sinhVien', 'lichThi'])
            ->where('MaLichThi', $this->maLichThi)
            ->get()
            ->map(function ($item) {
                return [
                    'MaSV' => $item->MaSV,
                    'HoTen' => $item->sinhVien->HoTen,
                    'MonThi' => $item->lichThi->TenMH,
                    'NgayThi' => \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($item->lichThi->NgayThi),
                    'TrangThaiDuThi' => $this->getTrangThaiLabel($item->TrangThaiDuThi),
                    'GhiChu' => $item->GhiChu ?? ''
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Mã Sinh Viên',
            'Họ Tên',
            'Môn Thi',
            'Ngày Thi',
            'Trạng Thái',
            'Ghi Chú'
        ];
    }

    public function title(): string
    {
        return 'Danh Sách Dự Thi';
    }

    private function getTrangThaiLabel($trangThai)
    {
        $labels = [
            'DangKy' => 'Đăng Ký',
            'DuThi' => 'Dự Thi',
            'VangMat' => 'Vắng Mặt',
            'KhongDuThi' => 'Không Dự Thi'
        ];

        return $labels[$trangThai] ?? $trangThai;
    }

    public function registerEvents(): array
    {
        return [
            \Maatwebsite\Excel\Events\AfterSheet::class => function(\Maatwebsite\Excel\Events\AfterSheet $event) {
                $event->sheet->getDefaultRowDimension()->setRowHeight(20);
                $event->sheet->getColumnDimension('A')->setWidth(15);
                $event->sheet->getColumnDimension('B')->setWidth(25);
                $event->sheet->getColumnDimension('C')->setWidth(20);
                $event->sheet->getColumnDimension('D')->setWidth(15);
                $event->sheet->getColumnDimension('E')->setWidth(20);
                $event->sheet->getColumnDimension('F')->setWidth(25);

                // Tiêu đề tổng
                $event->sheet->mergeCells('A1:F1');
                $event->sheet->setCellValue('A1', 'DANH SÁCH SINH VIÊN DỰ THI');
                $event->sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
            },
        ];
    }

    public function columnFormats(): array
    {
        return [
            'D' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Định dạng header
        $sheet->getStyle('A2:F2')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Định dạng dữ liệu
        $sheet->getStyle('A3:F' . ($sheet->getHighestRow()))->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        return $sheet;
    }
}