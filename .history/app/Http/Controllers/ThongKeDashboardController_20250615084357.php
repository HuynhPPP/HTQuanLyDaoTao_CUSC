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


        return view('thong-ke.dashboard', [
            'title' => 'Bảng Điều Khiển Thống Kê',
            'tongSinhVien' => $tongSinhVien,
            'tongNam' => $tongNam,
            'tongNu' => $tongNu,
            'tongGiaoVien' => $tongGiaoVien,
            'tongNamGV' => $tongNamGV,
            'tongNuGV' => $tongNuGV,
        ]);
    }
}
