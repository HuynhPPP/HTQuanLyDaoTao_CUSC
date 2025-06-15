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
        $thongKeGiaoVien = $this->thongKeService->thongKeGiangVien();
        $tongGiaoVien = $thongKeGiaoVien->sum('tong_so_luong');
        $tongNamGV = $thongKeGiaoVien->sum('nam');
        $tongNuGV = $thongKeGiaoVien->sum('nu');

        // Thống kê sinh viên theo chương trình đào tạo
        $sinhVienTheoChuongTrinh = $this->thongKeService->thongKeSinhVienTheoChuongTrinh();

        // Thống kê sinh viên theo lớp học
        $sinhVienTheoLop = $this->thongKeService->thongKeSinhVienTheoLop();

        // Thống kê môn học theo chương trình
        $monHocTheoChuongTrinh = $this->thongKeService->thongKeMonHocTheoChuongTrinh();
        $SoChuongTrinhDaoTao = $this->thongKeService->thongKeChuongTrinhDaoTao();

        // Thống kê tình trạng sinh viên
        $tinhTrangSinhVien = $this->thongKeService->thongKeTinhTrangSinhVien();

        return view('thong-ke.dashboard', [
            'title' => 'Bảng Điều Khiển Thống Kê',
            'tongSinhVien' => $tongSinhVien,
            'tongNam' => $tongNam,
            'tongNu' => $tongNu,
            'tongGiaoVien' => $tongGiaoVien,
            'tongNamGV' => $tongNamGV,
            'tongNuGV' => $tongNuGV,
            'sinhVienTheoChuongTrinh' => $sinhVienTheoChuongTrinh,
            'sinhVienTheoLop' => $sinhVienTheoLop,
            'monHocTheoChuongTrinh' => $monHocTheoChuongTrinh,
            'tinhTrangSinhVien' => $tinhTrangSinhVien
        ]);
    }
}
