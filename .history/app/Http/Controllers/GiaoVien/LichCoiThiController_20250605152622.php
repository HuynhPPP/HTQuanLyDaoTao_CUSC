<?php

namespace App\Http\Controllers\GiaoVien;

use App\Http\Controllers\Controller;
use App\Models\GiaoVien;
use App\Models\LichThi;
use App\Models\PhieuPhanCongThi;
use Illuminate\Support\Facades\Auth;

class LichCoiThiController extends Controller
{
    public function index()
    {
        // Lấy thông tin giảng viên hiện tại
        $giaoVien = GiaoVien::where('email', Auth::user()->email)->first();
        
        if (!$giaoVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên');
        }

        // Lấy danh sách lịch coi thi của giảng viên
        $lichCoiThi = PhieuPhanCongThi::with(['lichThi', 'lichThi.monHoc'])
            ->where('MaGV', $giaoVien->MaGV)
            ->orderBy('NgayThi', 'desc')
            ->get();

        return view('giaovien.lichthi.index', compact('lichCoiThi'));
    }

    public function chiTietLichThi($maLichThi)
    {
        // Lấy thông tin giảng viên hiện tại
        $giaoVien = GiaoVien::where('email', Auth::user()->email)->first();
        
        if (!$giaoVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên');
        }

        // Kiểm tra xem giảng viên có được phân công coi thi không
        $phanCongThi = PhieuPhanCongThi::where('MaGV', $giaoVien->MaGV)
            ->where('MaLichThi', $maLichThi)
            ->first();

        if (!$phanCongThi) {
            return redirect()->back()->with('error', 'Bạn không được phân công coi thi cho lịch thi này');
        }

        // Lấy chi tiết lịch thi
        $lichThi = LichThi::with(['monHoc', 'phongThi'])->findOrFail($maLichThi);

        return view('giaovien.lichthi.chi-tiet', compact('lichThi', 'phanCongThi'));
    }
} 