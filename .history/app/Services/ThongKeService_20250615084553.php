<?php

namespace App\Services;

use App\Models\SinhVien;
use App\Models\giaovien;
use App\Models\DiemThi;
use App\Models\ThongKe;
use App\Models\danhsachsv;
use App\Models\ChuongTrinh;
use Illuminate\Support\Facades\DB;

class ThongKeService
{
    /**
     * Thống kê sinh viên theo lớp
     */
    // public function thongKeSinhVien()
    // {
    //     return DanhSachSV::select(
    //         'lophoc.MaLop',
    //         DB::raw('COUNT(DISTINCT sinhvien.MaSV) as tong_so_luong'),
    //         DB::raw('SUM(CASE WHEN sinhvien.GioiTinh = 1 THEN 1 ELSE 0 END) as nam'),
    //         DB::raw('SUM(CASE WHEN sinhvien.GioiTinh = 0 THEN 1 ELSE 0 END) as nu')
    //     )
    //         ->join('sinhvien', 'danhsachsv.MaSV', '=', 'sinhvien.MaSV')
    //         ->join('lophoc', 'danhsachsv.MaLop', '=', 'lophoc.MaLop')
    //         ->groupBy('lophoc.MaLop')
    //         ->get();
    // }
    public function thongKeSinhVien()
    {
        return sinhvien::select(
            'MaSV',
            DB::raw('COUNT(DISTINCT sinhvien.MaSV) as tong_so_luong'),
            DB::raw('SUM(CASE WHEN sinhvien.GioiTinh = 1 THEN 1 ELSE 0 END) as nam'),
            DB::raw('SUM(CASE WHEN sinhvien.GioiTinh = 0 THEN 1 ELSE 0 END) as nu')
        )
            ->groupBy('sinhvien.MaSV')
            ->get();
    }

    public function thongKeGiangVien()
    {
        return giaovien::select(
            'MaGV',
            DB::raw('COUNT(DISTINCT giaovien.MaGV) as tong_so_luong'),
            DB::raw('SUM(CASE WHEN giaovien.GioiTinh = 1 THEN 1 ELSE 0 END) as nam'),
            DB::raw('SUM(CASE WHEN giaovien.GioiTinh = 0 THEN 1 ELSE 0 END) as nu')
        )
            ->groupBy('giaovien.MaGV')
            ->get();
    }

    public function thongKeSinhVienTheoChuongTrinh()
    {
        return DB::table('sinhvien as sv')
            ->join('chuongtrinh as ct', 'sv.MaChuongTrinh', '=', 'ct.MaChuongTrinh')
            ->select(
                'ct.TenChuongTrinh',
                DB::raw('COUNT(sv.MaSV) as tong_sinh_vien')
            )
            ->groupBy('ct.TenChuongTrinh')
            ->orderByDesc('tong_sinh_vien')
            ->get();
    }


    /**
     * Lưu thống kê vào database
     * @param string $loaiThongKe Loại thống kê
     * @param array $chiTiet Chi tiết thống kê
     * @return \App\Models\ThongKe
     */
    public function luuThongKe($loaiThongKe, $chiTiet)
    {
        return ThongKe::create([
            'loai_thong_ke' => $loaiThongKe,
            'chi_tiet' => json_encode($chiTiet),
            'tong_so_luong' => $chiTiet['tong_so_luong'] ?? 0,
            'diem_trung_binh' => $chiTiet['diem_trung_binh'] ?? null,
            'ty_le_dau' => $chiTiet['ty_le_dat'] ?? null
        ]);
    }
}