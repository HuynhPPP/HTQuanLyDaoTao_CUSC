<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TienTrinhHocTapExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $tienTrinh;

    public function __construct($tienTrinh)
    {
        $this->tienTrinh = $tienTrinh;
    }

    public function collection()
    {
        return $this->tienTrinh;
    }

    public function headings(): array
    {
        return [
            'STT',
            'Mã sinh viên',
            'Họ tên sinh viên',
            'Mã môn học',
            'Tên môn học',
            'Lớp',
            'Số tín chỉ',
            'Điểm lý thuyết',
            'Điểm thực hành',
            'Điểm dự án',
            'Điểm tổng',
            'Xếp loại',
            'Trạng thái',
            'Ngày hoàn thành',
            'Ghi chú'
        ];
    }

    public function map($tienTrinh): array
    {
        static $stt = 0;
        $stt++;

        return [
            $stt,
            $tienTrinh->MaSV,
            $tienTrinh->sinhVien->HoTen ?? 'N/A',
            $tienTrinh->MaMH,
            $tienTrinh->monHoc->TenMH ?? 'N/A',
            $tienTrinh->lopHoc->TenLop ?? 'N/A',
            $tienTrinh->SoTinChi,
            $tienTrinh->DiemLyThuyet ?? '-',
            $tienTrinh->DiemThucHanh ?? '-',
            $tienTrinh->DiemDuAn ?? '-',
            $tienTrinh->DiemTong ?? '-',
            $tienTrinh->XepLoai,
            $this->getTrangThaiText($tienTrinh->TrangThai),
            $tienTrinh->NgayHoanThanh ? \Carbon\Carbon::parse($tienTrinh->NgayHoanThanh)->format('d/m/Y') : '-',
            $tienTrinh->GhiChu ?? '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA']
                ]
            ]
        ];
    }

    private function getTrangThaiText($trangThai)
    {
        $trangThaiMap = [
            'DangKy' => 'Đã đăng ký',
            'DangHoc' => 'Đang học',
            'DaHoanThanh' => 'Đã hoàn thành',
            'ChuaHoanThanh' => 'Chưa hoàn thành'
        ];

        return $trangThaiMap[$trangThai] ?? $trangThai;
    }
} 