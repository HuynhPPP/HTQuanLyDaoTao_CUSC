<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CanhBaoService;
use App\Models\lophoc;
use App\Models\ChuongTrinh;

class CanhBaoController extends Controller
{
    protected $canhBaoService;

    public function __construct(CanhBaoService $canhBaoService)
    {
        $this->canhBaoService = $canhBaoService;
    }

    /**
     * Trang chính - Danh sách sinh viên có nguy cơ
     */
    public function index(Request $request)
    {
        $filters = $request->only(['muc_do', 'loai_canh_bao', 'ma_lop']);
        
        // Đảm bảo tất cả các key filter đều có giá trị mặc định
        $filters = array_merge([
            'muc_do' => '',
            'loai_canh_bao' => '',
            'ma_lop' => ''
        ], $filters);
        
        $danhSachCanhBao = $this->canhBaoService->layDanhSachCanhBao($filters);
        $thongKe = $this->canhBaoService->thongKeCanhBao();
        
        // Dữ liệu cho filter
        $dsLop = lophoc::with('loaidaotao')->get();
        
        // Cảnh báo mới nhất (7 ngày qua)
        $canhBaoMoiNhat = $danhSachCanhBao->filter(function($item) {
            return $item['NgayTao'] >= now()->subDays(7);
        })->take(10);

        return view('canh-bao.index', compact(
            'danhSachCanhBao', 
            'thongKe', 
            'dsLop',
            'canhBaoMoiNhat',
            'filters'
        ));
    }

    /**
     * Chạy hệ thống cảnh báo
     */
    public function chayCanhBao()
    {
        try {
            $ketQua = $this->canhBaoService->chayTatCaCanhBao();
            
            $message = "Đã phát hiện {$ketQua['tong_canh_bao']} sinh viên có nguy cơ";
            $message .= " (Cao: {$ketQua['canh_bao_cao']}, Trung bình: {$ketQua['canh_bao_trung_binh']}, Thấp: {$ketQua['canh_bao_thap']})";
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'tong_canh_bao' => $ketQua['tong_canh_bao'],
                    'canh_bao_cao' => $ketQua['canh_bao_cao'],
                    'canh_bao_trung_binh' => $ketQua['canh_bao_trung_binh'],
                    'canh_bao_thap' => $ketQua['canh_bao_thap']
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xem chi tiết sinh viên có nguy cơ
     */
    public function chiTiet($id)
    {
        $canhBao = $this->canhBaoService->layChiTietCanhBao($id);

        if (!$canhBao) {
            return redirect()->route('canh-bao.index')->with('error', 'Không tìm thấy thông tin sinh viên');
        }

        return view('canh-bao.chi-tiet', compact('canhBao'));
    }

    /**
     * API lấy danh sách cảnh báo
     */
    public function apiCanhBao(Request $request)
    {
        $filters = $request->only(['muc_do', 'loai_canh_bao', 'ma_lop']);
        $danhSachCanhBao = $this->canhBaoService->layDanhSachCanhBao($filters);

        return response()->json([
            'success' => true,
            'data' => $danhSachCanhBao
        ]);
    }

    /**
     * API thống kê
     */
    public function apiThongKe()
    {
        $thongKe = $this->canhBaoService->thongKeCanhBao();

        return response()->json([
            'success' => true,
            'data' => $thongKe
        ]);
    }
}