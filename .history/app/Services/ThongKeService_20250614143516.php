<?php

namespace App\Services;

use App\Models\SinhVien;
use App\Models\DiemThi;
use App\Models\ThongKe;
use App\Models\ChuongTrinh;
use Illuminate\Support\Facades\DB;

class ThongKeService 
{
    /**
     * Thống kê sinh viên theo khoa và lớp
     */
    public function thongKeSinhVien()
    {
        return SinhVien::select(
            'MaKhoa', 
            DB::raw('COUNT(*) as tong_so_luong'),
            DB::raw('SUM(CASE WHEN GioiTinh = 1 THEN 1 ELSE 0 END) as nam'),
            DB::raw('SUM(CASE WHEN GioiTinh = 0 THEN 1 ELSE 0 END) as nu')
        )
        ->groupBy('MaKhoa')
        ->get();
    }

    /**
     * Thống kê điểm số
     */
    public function thongKeDiem($maLop = null, $hocKy = null)
    {
        $query = DiemThi::select(
            'MaMH',
            DB::raw('AVG(Diem) as diem_trung_binh'),
            DB::raw('COUNT(*) as tong_so_luong'),
            DB::raw('SUM(CASE WHEN Diem >= 5 THEN 1 ELSE 0 END) as so_luong_dat'),
            DB::raw('(SUM(CASE WHEN Diem >= 5 THEN 1 ELSE 0 END) / COUNT(*)) * 100 as ty_le_dat')
        );

        if ($maLop) {
            $query->where('MaLop', $maLop);
        }

        if ($hocKy) {
            $query->where('MaHK', $hocKy);
        }

        return $query->groupBy('MaMH')->get();
    }

    /**
     * Phân loại học lực
     */
    public function layPhanLoaiHocLuc()
    {
        return DiemThi::select(
            'MaSV',
            DB::raw('AVG(Diem) as diem_trung_binh'),
            DB::raw('CASE 
                WHEN AVG(Diem) >= 9.0 THEN "Xuất sắc"
                WHEN AVG(Diem) >= 8.0 THEN "Giỏi"
                WHEN AVG(Diem) >= 6.5 THEN "Khá"
                WHEN AVG(Diem) >= 5.0 THEN "Trung bình"
                ELSE "Yếu"
            END as hoc_luc')
        )
        ->groupBy('MaSV')
        ->get();
    }

    /**
     * Thống kê khóa học
     */
    public function thongKeKhoaHoc()
    {
        return ChuongTrinh::select(
            'MaChuongTrinh',
            'TenChuongTrinh',
            'TenKhoaDaoTao',
            DB::raw('COUNT(DISTINCT sv.MaSV) as tong_sinh_vien'),
            DB::raw('AVG(dt.Diem) as diem_trung_binh')
        )
        ->leftJoin('chuongtrinh_monhoc as ctmh', 'chuongtrinh.MaChuongTrinh', '=', 'ctmh.MaChuongTrinh')
        ->leftJoin('monhoc as mh', 'ctmh.MaMH', '=', 'mh.MaMH')
        ->leftJoin('diemthi as dt', 'mh.MaMH', '=', 'dt.MaMH')
        ->leftJoin('sinhvien as sv', 'dt.MaSV', '=', 'sv.MaSV')
        ->groupBy('MaChuongTrinh', 'TenChuongTrinh', 'TenKhoaDaoTao')
        ->get();
    }

    /**
     * Phân loại học lực
     */
    public function phanLoaiHocLuc($diemTrungBinh)
    {
        if ($diemTrungBinh >= 9.0) return 'Xuất sắc';
        if ($diemTrungBinh >= 8.0) return 'Giỏi';
        if ($diemTrungBinh >= 6.5) return 'Khá';
        if ($diemTrungBinh >= 5.0) return 'Trung bình';
        return 'Yếu';
    }

    /**
     * Lưu thống kê vào database
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