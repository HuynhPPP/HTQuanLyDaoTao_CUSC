<?php

namespace App\Http\Controllers;

use App\Services\ThongKeService;
use Illuminate\Http\Request;

class ThongKeDashboardController extends Controller
{
    protected $thongKeService;

    public function __construct(ThongKeService $thongKeService)
    {
        $this->thongKeService = $thongKeService;
    }

    public function index()
    {
        // Thống kê sinh viên
        $thongKeSinhVien = $this->thongKeService->thongKeSinhVien();
        $tongSinhVien = $thongKeSinhVien->sum('tong_so_luong');
        $tongNam = $thongKeSinhVien->sum('nam');
        $tongNu = $thongKeSinhVien->sum('nu');

        // Thống kê giáo viên
        $thongKeSinhVien = $this->thongKeService->thongKeSinhVien();
        $tongSinhVien = $thongKeSinhVien->sum('tong_so_luong');
        $tongNam = $thongKeSinhVien->sum('nam');
        $tongNu = $thongKeSinhVien->sum('nu');

        // Thống kê điểm số
        $thongKeDiem = $this->thongKeService->thongKeDiem();
        $diemTrungBinh = $thongKeDiem->avg('diem_trung_binh');
        $tyLeDat = $thongKeDiem->avg('ty_le_dat');

        // Thống kê học lực
        $phanLoaiHocLuc = $this->thongKeService->layPhanLoaiHocLuc();
        $hocLucPhanLoai = $phanLoaiHocLuc->groupBy('hoc_luc')
            ->map(function ($group) {
                return $group->count();
            });

        // Thống kê khóa học
        $thongKeKhoaHoc = $this->thongKeService->thongKeKhoaHoc();
        $tongKhoaHoc = $thongKeKhoaHoc->count();
        $diemTrungBinhKhoaHoc = $thongKeKhoaHoc->avg('diem_trung_binh');

        return view('thong-ke.dashboard', [
            'title' => 'Bảng Điều Khiển Thống Kê',
            'tongSinhVien' => $tongSinhVien,
            'tongNam' => $tongNam,
            'tongNu' => $tongNu,
            'diemTrungBinh' => round($diemTrungBinh, 2),
            'tyLeDat' => round($tyLeDat, 2),
            'hocLucPhanLoai' => $hocLucPhanLoai,
            'tongKhoaHoc' => $tongKhoaHoc,
            'diemTrungBinhKhoaHoc' => round($diemTrungBinhKhoaHoc, 2)
        ]);
    }
}
