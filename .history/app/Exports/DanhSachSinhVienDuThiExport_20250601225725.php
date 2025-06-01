<?php

namespace App\Exports;

use App\Models\LichThi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DanhSachSinhVienDuThiExport implements FromCollection, WithHeadings, WithTitle, WithEvents
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
        return [
            'STT',
            'Mã SV',
            'Họ Tên',
            'Trạng Thái',
            'Ghi Chú'
        ];
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

                $sheet->insertNewRowBefore(1, 12); // chèn 12 dòng đầu
    
                // === Tiêu đề tổ chức ===
                $sheet->mergeCells('A1:E1')->setCellValue('A1', 'TRUNG TÂM CÔNG NGHỆ PHẦN MỀM ĐẠI HỌC CẦN THƠ');
                $sheet->mergeCells('A2:E2')->setCellValue('A2', 'CANTHO UNIVERSITY SOFTWARE CENTER');
                $sheet->mergeCells('A3:E3')->setCellValue('A3', 'Khu III, Đại học Cần Thơ - 01 Lý Tự Trọng, TP. Cần Thơ - Tel: 0292.3731072 & Fax: 0292.3731071 - Email: cusc@ctu.edu.vn');

                // === Thông tin lịch thi ===
                $sheet->mergeCells('A5:E5')->setCellValue('A5', 'DANH SÁCH SINH VIÊN DỰ THI');
                $sheet->mergeCells('A6:E6')->setCellValue('A6', 'Môn: ' . $lichThi->TenMH . ' | Lớp: ' . $lichThi->MaLop);
                $sheet->mergeCells('A7:E7')->setCellValue('A7', 'Ngày thi: ' . \Carbon\Carbon::parse($lichThi->NgayThi)->format('d/m/Y') . ' - Phòng: ' . $lichThi->PhongThi);
                $sheet->mergeCells('A8:E8')->setCellValue('A8', 'Cán bộ coi thi: ' . $canBoList->implode(', '));

                // === Style tiêu đề ===
                foreach (range(1, 8) as $row) {
                    $sheet->getStyle("A$row")->getFont()->setBold(true);
                    $sheet->getStyle("A$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                $sheet->getStyle('A5')->getFont()->setSize(14);

                // === Chữ ký ===
                // $lastDataRow = 13 + \App\Models\SinhVienDuThi::where('MaLichThi', $this->maLichThi)->count();

                // $sheet->setCellValue("B{$lastDataRow}", 'CÁN BỘ COI THI 1');
                // $sheet->setCellValue("D{$lastDataRow}", 'CÁN BỘ COI THI 2');
                // $sheet->setCellValue("E{$lastDataRow}", 'ĐẠI DIỆN CUSC');

                // foreach (['B', 'D', 'E'] as $col) {
                //     $sheet->getStyle("{$col}{$lastDataRow}")->getFont()->setBold(true);
                //     $sheet->getStyle("{$col}{$lastDataRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                // }

                // $sheet->getRowDimension($lastDataRow)->setRowHeight(30); // tăng chiều cao dòng ký tên
            },
        ];
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

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('CUSC Logo');
        $drawing->setDescription('Logo Trung tâm Công nghệ Phần mềm');
        $drawing->setPath(asset('images/banner_cusc.png')); // đường dẫn logo
        $drawing->setHeight(80);
        $drawing->setCoordinates('A1'); // vị trí chèn (ô A1)
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(5);

        return [$drawing];
    }
}
