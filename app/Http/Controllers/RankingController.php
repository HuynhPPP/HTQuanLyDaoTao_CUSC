<?php

namespace App\Http\Controllers;

use App\Services\RankingService;
use App\Models\lophoc;
use App\Models\ChuongTrinh;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    protected $rankingService;

    public function __construct(RankingService $rankingService)
    {
        $this->rankingService = $rankingService;
    }

    /**
     * Hiển thị trang chọn lớp để xem xếp hạng
     */
    public function index()
    {
        $dsLop = lophoc::with('loaidaotao')->get();
        $dsChuongTrinh = ChuongTrinh::all();
        
        return view('ranking.index', compact('dsLop', 'dsChuongTrinh'));
    }

    /**
     * Xếp hạng sinh viên theo lớp
     */
    public function xepHangLop($MaLop)
    {
        $lop = lophoc::with('loaidaotao')->find($MaLop);
        
        if (!$lop) {
            return redirect()->back()->with('error', 'Không tìm thấy lớp học');
        }

        $bangXepHang = $this->rankingService->xepHangSinhVienTheoLop($MaLop);
        $thongKe = $this->rankingService->thongKeXepHangTheoLop($MaLop);

        return view('ranking.lop', compact('lop', 'bangXepHang', 'thongKe'));
    }

    /**
     * Top sinh viên xuất sắc toàn trường
     */
    public function topSinhVien()
    {
        $topSinhVien = $this->rankingService->topSinhVienXuatSac(20);
        
        return view('ranking.top', compact('topSinhVien'));
    }

    /**
     * Xếp hạng theo chương trình đào tạo
     */
    public function xepHangChuongTrinh($MaChuongTrinh)
    {
        $chuongTrinh = ChuongTrinh::find($MaChuongTrinh);
        
        if (!$chuongTrinh) {
            return redirect()->back()->with('error', 'Không tìm thấy chương trình đào tạo');
        }

        $bangXepHang = $this->rankingService->xepHangTheoChuongTrinh($MaChuongTrinh);

        return view('ranking.chuong-trinh', compact('chuongTrinh', 'bangXepHang'));
    }

    /**
     * So sánh hiệu suất các lớp
     */
    public function soSanhLop()
    {
        $soSanhLop = $this->rankingService->soSanhHieuSuatCacLop();
        
        return view('ranking.so-sanh-lop', compact('soSanhLop'));
    }

    /**
     * API trả về dữ liệu xếp hạng cho AJAX
     */
    public function apiXepHangLop($MaLop)
    {
        $bangXepHang = $this->rankingService->xepHangSinhVienTheoLop($MaLop);
        $thongKe = $this->rankingService->thongKeXepHangTheoLop($MaLop);
        
        return response()->json([
            'success' => true,
            'data' => [
                'bang_xep_hang' => $bangXepHang,
                'thong_ke' => $thongKe
            ]
        ]);
    }

    /**
     * API trả về top sinh viên
     */
    public function apiTopSinhVien($limit = 10)
    {
        $topSinhVien = $this->rankingService->topSinhVienXuatSac($limit);
        
        return response()->json([
            'success' => true,
            'data' => $topSinhVien
        ]);
    }
}
