<?php

namespace App\Http\Controllers;

use App\Services\ThongKeService;
use Illuminate\Http\Request;

class ThongKeController extends Controller
{
    protected $thongKeService;

    public function __construct(ThongKeService $thongKeService)
    {
        $this->thongKeService = $thongKeService;
    }

    /**
     * Trang thống kê sinh viên
     */
    public function thongKeSinhVien()
    {
        $thongKe = $this->thongKeService->thongKeSinhVien();
        
        return view('thong-ke.sinh-vien', [
            'title' => 'Thống Kê Sinh Viên',
            'thongKe' => $thongKe
        ]);
    }

    /**
     * Trang thống kê điểm số
     */
    public function thongKeDiem(Request $request)
    {
        $maLop = $request->input('ma_lop');
        $hocKy = $request->input('hoc_ky');

        $thongKeDiem = $this->thongKeService->thongKeDiem($maLop, $hocKy);
        
        return view('thong-ke.diem-so', [
            'title' => 'Thống Kê Điểm Số',
            'thongKeDiem' => $thongKeDiem
        ]);
    }

    /**
     * Trang phân loại học lực
     */
    public function phanLoaiHocLuc(Request $request)
    {
        $hocLuc = $this->thongKeService->layPhanLoaiHocLuc();
        
        return view('thong-ke.hoc-luc', [
            'title' => 'Phân Loại Học Lực',
            'hocLuc' => $hocLuc
        ]);
    }

    /**
     * Trang thống kê khóa học
     */
    public function thongKeKhoaHoc()
    {
        $khoaHoc = $this->thongKeService->thongKeKhoaHoc();
        
        return view('thong-ke.khoa-hoc', [
            'title' => 'Thống Kê Khóa Học',
            'khoaHoc' => $khoaHoc
        ]);
    }
}
