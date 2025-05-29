<?php

namespace App\Exports;

use App\Models\sinhvien;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DanhSachSinhVienLopExport implements FromCollection, WithHeadings
{
    protected $maLop;

    public function __construct($maLop)
    {
        $this->maLop = $maLop;
    }

    public function collection()
    {
        return sinhvien::join('danhsachsv', 'SinhVien.MaSV', '=', 'danhsachsv.MaSV')
            ->where('danhsachsv.MaLop', $this->maLop)
            ->select(
                'SinhVien.MaSV', 
                'SinhVien.HoTen', 
                'SinhVien.NgaySinh',
                'SinhVien.GioiTinh',
                'SinhVien.Email', 
                'SinhVien.Sdt'
            )
            ->get();
    }

    public function headings(): array
    {
        return [
            'Mã Sinh Viên',
            'Họ Tên',
            'Ngày Sinh',
            'Giới Tính',
            'Email',
            'Số Điện Thoại'
        ];
    }
}