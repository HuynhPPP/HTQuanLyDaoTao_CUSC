<?php

namespace App\Http\Controllers\Front\GiaoVien;

use App\Http\Controllers\Controller;
use App\Services\ThamDuService;
use App\Models\GiangDay;
use App\Models\LopHoc;
use App\Models\MonHoc;
use App\Models\DiemThi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ThamDuController extends Controller
{
    protected $thamDuService;

    public function __construct(ThamDuService $thamDuService)
    {
        $this->thamDuService = $thamDuService;
    }

    /**
     * Trang chủ thống kê tham dự
     */
    public function index()
    {
        $maGV = Auth::user()->MaTaiKhoan ?? 'GV001'; // Lấy mã GV từ session hoặc default
        
        // Lấy danh sách lớp mà giảng viên đang dạy
        $danhSachLop = $this->thamDuService->layDanhSachLopGiangVien($maGV);
        
        // Thống kê tổng quan
        $thongKeTongQuan = collect();
        foreach ($danhSachLop as $lop) {
            $thongKeLop = $this->thamDuService->thongKeTongQuanLop($lop['MaLop']);
            $thongKeTongQuan->push(array_merge($thongKeLop, [
                'MaLop' => $lop['MaLop'],
                'TenLop' => $lop['TenLop'],
                'SoMonHoc' => $lop['SoMonHoc']
            ]));
        }

        return view('frontend.giangvien.tham-du.index', compact('danhSachLop', 'thongKeTongQuan'));
    }

    /**
     * Chi tiết tham dự theo lớp
     */
    public function chiTietLop(Request $request, $maLop)
    {
        $lopHoc = LopHoc::find($maLop);
        if (!$lopHoc) {
            return redirect()->back()->with('error', 'Không tìm thấy lớp học');
        }

        // Lấy danh sách môn học của lớp
        $monHocs = GiangDay::with('monHoc')
            ->where('MaLop', $maLop)
            ->get()
            ->pluck('monHoc')
            ->unique('MaMH');

        // Thống kê tham dự theo lớp
        $thongKeThamDu = $this->thamDuService->thongKeThamDuTheoLop($maLop);
        $thongKeTongQuan = $this->thamDuService->thongKeTongQuanLop($maLop);

        // Lọc theo môn học nếu có
        $maMH = $request->get('maMH');
        if ($maMH) {
            $thongKeThamDu = $this->thamDuService->thongKeThamDuTheoMon($maLop, $maMH);
        }

        return view('frontend.giangvien.tham-du.chi-tiet-lop', compact(
            'lopHoc', 
            'monHocs', 
            'thongKeThamDu', 
            'thongKeTongQuan',
            'maMH'
        ));
    }

    /**
     * Chi tiết tham dự của sinh viên
     */
    public function chiTietSinhVien($maLop, $maSV)
    {
        $sinhVien = \App\Models\sinhvien::find($maSV);
        $lopHoc = LopHoc::find($maLop);
        
        if (!$sinhVien || !$lopHoc) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin sinh viên hoặc lớp học');
        }

        // Lấy danh sách môn học của sinh viên trong lớp
        $monHocs = DiemThi::with('monHoc')
            ->where('MaSV', $maSV)
            ->where('MaLop', $maLop)
            ->get()
            ->pluck('monHoc')
            ->unique('MaMH');

        $chiTietThamDu = $this->thamDuService->layChiTietThamDuSV($maSV, $maLop);

        return view('frontend.giangvien.tham-du.chi-tiet-sinh-vien', compact(
            'sinhVien',
            'lopHoc',
            'monHocs',
            'chiTietThamDu'
        ));
    }

    /**
     * Chi tiết tham dự theo môn học
     */
    public function chiTietMonHoc($maLop, $maMH)
    {
        $lopHoc = LopHoc::find($maLop);
        $monHoc = MonHoc::find($maMH);
        
        if (!$lopHoc || !$monHoc) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin lớp học hoặc môn học');
        }

        $thongKeThamDu = $this->thamDuService->thongKeThamDuTheoMon($maLop, $maMH);

        // Thống kê tổng quan môn học
        $tongSV = $thongKeThamDu->count();
        $thamDuTot = $thongKeThamDu->where('TyLeThamDu', '>=', 80)->count();
        $thamDuTrungBinh = $thongKeThamDu->where('TyLeThamDu', '>=', 60)->where('TyLeThamDu', '<', 80)->count();
        $thamDuYeu = $thongKeThamDu->where('TyLeThamDu', '<', 60)->count();
        $tyLeThamDuTB = $thongKeThamDu->avg('TyLeThamDu');

        $thongKeTongQuan = [
            'tong_sinh_vien' => $tongSV,
            'tham_du_tot' => $thamDuTot,
            'tham_du_trung_binh' => $thamDuTrungBinh,
            'tham_du_yeu' => $thamDuYeu,
            'ty_le_tham_du_tb' => round($tyLeThamDuTB, 2)
        ];

        return view('frontend.giangvien.tham-du.chi-tiet-mon-hoc', compact(
            'lopHoc',
            'monHoc',
            'thongKeThamDu',
            'thongKeTongQuan'
        ));
    }

    /**
     * Xuất báo cáo Excel
     */
    public function xuatBaoCao(Request $request, $maLop)
    {
        $maMH = $request->get('maMH');
        
        $baoCao = $this->thamDuService->xuatBaoCaoThamDu($maLop, $maMH);
        
        // Tạo Excel file
        $filename = $baoCao['filename'];
        $data = $baoCao['data'];
        
        // Tạo file Excel đơn giản
        $excelData = [];
        $excelData[] = ['Mã SV', 'Họ tên', 'Tỷ lệ tham dự (%)', 'Xếp loại', 'Số lần có điểm', 'Tổng số buổi học'];
        
        foreach ($data as $item) {
            $excelData[] = [
                $item['MaSV'],
                $item['HoTen'],
                $item['TyLeThamDu'],
                $item['XepLoaiThamDu'],
                $item['SoLanCoDiem'] ?? 0,
                $item['TongSoBuoiHoc'] ?? 0
            ];
        }

        // Tạo file CSV đơn giản (có thể nâng cấp thành Excel sau)
        $csvContent = '';
        foreach ($excelData as $row) {
            $csvContent .= implode(',', $row) . "\n";
        }

        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * API lấy dữ liệu thống kê cho biểu đồ
     */
    public function apiThongKe(Request $request)
    {
        $maLop = $request->get('maLop');
        $maMH = $request->get('maMH');

        if ($maMH) {
            $thongKe = $this->thamDuService->thongKeThamDuTheoMon($maLop, $maMH);
        } else {
            $thongKe = $this->thamDuService->thongKeThamDuTheoLop($maLop);
        }

        // Chuẩn bị dữ liệu cho biểu đồ
        $data = [
            'labels' => $thongKe->pluck('HoTen')->toArray(),
            'datasets' => [
                [
                    'label' => 'Tỷ lệ tham dự (%)',
                    'data' => $thongKe->pluck('TyLeThamDu')->toArray(),
                    'backgroundColor' => $thongKe->map(function($item) {
                        if ($item['TyLeThamDu'] >= 80) return 'rgba(40, 167, 69, 0.8)';
                        if ($item['TyLeThamDu'] >= 60) return 'rgba(255, 193, 7, 0.8)';
                        return 'rgba(220, 53, 69, 0.8)';
                    })->toArray(),
                    'borderColor' => $thongKe->map(function($item) {
                        if ($item['TyLeThamDu'] >= 80) return 'rgba(40, 167, 69, 1)';
                        if ($item['TyLeThamDu'] >= 60) return 'rgba(255, 193, 7, 1)';
                        return 'rgba(220, 53, 69, 1)';
                    })->toArray(),
                    'borderWidth' => 1
                ]
            ]
        ];

        return response()->json($data);
    }
}
