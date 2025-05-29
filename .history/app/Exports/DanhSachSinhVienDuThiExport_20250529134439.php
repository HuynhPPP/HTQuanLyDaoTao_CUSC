<?php

namespace App\Exports;

use App\Models\SinhVienDuThi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class DanhSachSinhVienDuThiExport implements FromCollection, WithHeadings, WithTitle
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
            ->map(function($item) {
                return [
                    'MaSV' => $item->MaSV,
                    'HoTen' => $item->sinhVien->HoTen,
                    'MonThi' => $item->lichThi->TenMH,
                    'NgayThi' => $item->lichThi->NgayThi,
                    'TrangThaiDuThi' => $this->getTrangThaiLabel($item->TrangThaiDuThi),
                    'GhiChu' => $item->GhiChu
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
}