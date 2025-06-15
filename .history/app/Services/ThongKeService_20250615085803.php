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
        return DB::table('danhsachsv as dssv')
            ->join('lophoc as lh', 'dssv.MaLop', '=', 'lh.MaLop')
            ->join('chuongtrinh as ct', 'lh.MaChuongTrinh', '=', 'ct.MaChuongTrinh')
            ->select(
                'ct.TenChuongTrinh',
                DB::raw('COUNT(DISTINCT dssv.MaSV) as so_luong')
            )
            ->groupBy('ct.TenChuongTrinh')
            ->orderByDesc('so_luong')
            ->get();
    }

    public function thongKeSinhVienTheoLop()
    {
        return DB::table('danhsachsv as dssv')
            ->join('lophoc as lh', 'dssv.MaLop', '=', 'lh.MaLop')
            ->select(
                'lh.MaLop',
                'lh.TenLop', 
                DB::raw('COUNT(DISTINCT dssv.MaSV) as so_luong')
            )
            ->groupBy('lh.MaLop', 'lh.TenLop')
            ->orderByDesc('so_luong')
            ->get();
    }

    public function thongKeMonHocTheoChuongTrinh()
    {
        return DB::table('chuongtrinh as ct')
            ->leftJoin('chuongtrinh_monhoc as ctmh', 'ct.MaChuongTrinh', '=', 'ctmh.MaChuongTrinh')
            ->select(
                'ct.MaChuongTrinh',
                'ct.TenChuongTrinh',
                DB::raw('COUNT(DISTINCT ctmh.MaMH) as so_mon_hoc')
            )
            ->groupBy('ct.MaChuongTrinh', 'ct.TenChuongTrinh')
            ->orderByDesc('so_mon_hoc')
            ->get();
    }

    public function thongKeTinhTrangSinhVien()
    {
        return DB::table('sinhvien')
            ->select(
                'TinhTrangHocTap', 
                DB::raw('COUNT(*) as so_luong'),
                DB::raw('
                    CASE 
                        WHEN TinhTrangHocTap = "DangHoc" THEN "Đang Học" 
                        WHEN TinhTrangHocTap = 2 THEN "Bảo Lưu" 
                        WHEN TinhTrangHocTap = 3 THEN "Thôi Học" 
                        WHEN TinhTrangHocTap = 4 THEN "Tốt Nghiệp" 
                        ELSE "Khác" 
                    END as ten_tinh_trang
                ')
            )
            ->groupBy('TinhTrangHocTap')
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