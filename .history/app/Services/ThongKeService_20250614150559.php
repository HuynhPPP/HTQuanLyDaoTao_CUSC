<?php

namespace App\Services;

use App\Models\SinhVien;
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
    public function thongKeSinhVien()
    {
        return DanhSachSV::select(
            'lophoc.MaLop',
            DB::raw('COUNT(DISTINCT sinhvien.MaSV) as tong_so_luong'),
            DB::raw('SUM(CASE WHEN sinhvien.GioiTinh = 1 THEN 1 ELSE 0 END) as nam'),
            DB::raw('SUM(CASE WHEN sinhvien.GioiTinh = 0 THEN 1 ELSE 0 END) as nu')
        )
            ->join('sinhvien', 'danhsachsv.MaSV', '=', 'sinhvien.MaSV')
            ->join('lophoc', 'danhsachsv.MaLop', '=', 'lophoc.MaLop')
            ->groupBy('lophoc.MaLop')
            ->get();
    }
    public function thongKeSinhVien()
    {
        return DanhSachSV::select(
            'lophoc.MaLop',
            DB::raw('COUNT(DISTINCT sinhvien.MaSV) as tong_so_luong'),
            DB::raw('SUM(CASE WHEN sinhvien.GioiTinh = 1 THEN 1 ELSE 0 END) as nam'),
            DB::raw('SUM(CASE WHEN sinhvien.GioiTinh = 0 THEN 1 ELSE 0 END) as nu')
        )
            ->join('sinhvien', 'danhsachsv.MaSV', '=', 'sinhvien.MaSV')
            ->join('lophoc', 'danhsachsv.MaLop', '=', 'lophoc.MaLop')
            ->groupBy('lophoc.MaLop')
            ->get();
    }

    /**
     * Thống kê điểm số
     * @param string|null $maLop Mã lớp
     * @param string|null $hocKy Học kỳ
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function thongKeDiem($maLop = null, $hocKy = null)
    {
        $query = DiemThi::select(
            'MaMH',
            DB::raw('AVG(DiemTong) as diem_trung_binh'),
            DB::raw('COUNT(*) as tong_so_luong'),
            DB::raw('SUM(CASE WHEN DiemTong >= 5 THEN 1 ELSE 0 END) as so_luong_dat'),
            DB::raw('(SUM(CASE WHEN DiemTong >= 5 THEN 1 ELSE 0 END) * 100.0 / COUNT(*)) as ty_le_dat')
        )
            ->whereNotNull('DiemTong');

        if ($maLop) {
            $query->where('MaLop', $maLop);
        }

        if ($hocKy) {
            $query->where('MaHK', $hocKy);
        }

        return $query->groupBy('MaMH')
            ->orderBy('diem_trung_binh', 'desc')
            ->get();
    }

    /**
     * Phân loại học lực
     */
    public function layPhanLoaiHocLuc()
    {
        return DiemThi::select(
            'MaSV',
            DB::raw('AVG(DiemTong) as diem_trung_binh'),
            DB::raw('CASE 
                WHEN AVG(DiemTong) >= 9.0 THEN "Xuất sắc"
                WHEN AVG(DiemTong) >= 8.0 THEN "Giỏi"
                WHEN AVG(DiemTong) >= 6.5 THEN "Khá"
                WHEN AVG(DiemTong) >= 5.0 THEN "Trung bình"
                ELSE "Yếu"
            END as hoc_luc')
        )
            ->whereNotNull('DiemTong')
            ->groupBy('MaSV')
            ->get();
    }

    /**
     * Thống kê khóa học
     */
    public function thongKeKhoaHoc()
    {
        return ChuongTrinh::select(
            'chuongtrinh.MaChuongTrinh',
            'chuongtrinh.TenChuongTrinh',
            'chuongtrinh.TenKhoaDaoTao',
            DB::raw('COUNT(DISTINCT sv.MaSV) as tong_sinh_vien'),
            DB::raw('AVG(dt.DiemTong) as diem_trung_binh')
        )
            ->leftJoin('chuongtrinh_monhoc as ctmh', 'chuongtrinh.MaChuongTrinh', '=', 'ctmh.MaChuongTrinh')
            ->leftJoin('monhoc as mh', 'ctmh.MaMH', '=', 'mh.MaMH')
            ->leftJoin('diemthi as dt', 'mh.MaMH', '=', 'dt.MaMH')
            ->leftJoin('sinhvien as sv', 'dt.MaSV', '=', 'sv.MaSV')
            ->groupBy('chuongtrinh.MaChuongTrinh', 'chuongtrinh.TenChuongTrinh', 'chuongtrinh.TenKhoaDaoTao')
            ->orderBy('diem_trung_binh', 'desc')
            ->get();
    }

    /**
     * Phân loại học lực
     * @param float $diemTrungBinh Điểm trung bình
     * @return string Loại học lực
     */
    public function phanLoaiHocLuc($diemTrungBinh)
    {
        if ($diemTrungBinh >= 9.0)
            return 'Xuất sắc';
        if ($diemTrungBinh >= 8.0)
            return 'Giỏi';
        if ($diemTrungBinh >= 6.5)
            return 'Khá';
        if ($diemTrungBinh >= 5.0)
            return 'Trung bình';
        return 'Yếu';
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