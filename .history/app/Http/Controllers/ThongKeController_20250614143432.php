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
     * Thống kê sinh viên theo khoa
     */
    public function thongKeSinhVien()
    {
        $thongKe = $this->thongKeService->thongKeSinhVien();
        
        return response()->json([
            'success' => true,
            'data' => $thongKe
        ]);
    }

    /**
     * Thống kê điểm số
     */
    public function thongKeDiem(Request $request)
    {
        $maLop = $request->input('ma_lop');
        $hocKy = $request->input('hoc_ky');

        $thongKe = $this->thongKeService->thongKeDiem($maLop, $hocKy);
        
        return response()->json([
            'success' => true,
            'data' => $thongKe
        ]);
    }

    /**
     * Phân loại học lực
     */
    public function phanLoaiHocLuc(Request $request)
    {
        $diemTrungBinh = $request->input('diem_trung_binh');
        $hocLuc = $this->thongKeService->phanLoaiHocLuc($diemTrungBinh);
        
        return response()->json([
            'success' => true,
            'hoc_luc' => $hocLuc
        ]);
    }

    /**
     * Xuất báo cáo thống kê
     */
    public function xuatBaoCao(Request $request)
    {
        $loaiThongKe = $request->input('loai_thong_ke', 'sinh_vien');
        
        $chiTiet = match($loaiThongKe) {
            'sinh_vien' => $this->thongKeService->thongKeSinhVien(),
            'diem_so' => $this->thongKeService->thongKeDiem(),
            default => []
        };

        $baoCao = $this->thongKeService->luuThongKe($loaiThongKe, $chiTiet);
        
        return response()->json([
            'success' => true,
            'bao_cao' => $baoCao
        ]);
    }
}
