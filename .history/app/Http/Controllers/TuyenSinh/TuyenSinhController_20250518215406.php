<?php

namespace App\Http\Controllers\TuyenSinh;

use App\Http\Controllers\Controller;
use App\Models\HoSoTuyenSinh;
use App\Models\ThongTinTuyenSinh;
use Illuminate\Http\Request;

class TuyenSinhController extends Controller
{
    public function index()
    {
        $dotTuyenSinh = ThongTinTuyenSinh::orderBy('NamTS', 'desc')
            ->orderBy('DotTS', 'desc')
            ->get();
        return view('quanly_tuyensinh.tuyensinh.index', compact('dotTuyenSinh'));
    }

    public function danhSachHoSo($maTS)
    {
        $hoSo = HoSoTuyenSinh::with('sinhvien')
            ->where('MaTS', $maTS)
            ->get();
        return view('quanly_tuyensinh.tuyensinh.danhsach_hoso', compact('hoSo'));
    }

    public function taoHoSo(Request $request)
    {
        $request->validate([
            'MaSV' => 'required',
            'MaTS' => 'required',
            'NgayNopHS' => 'required|date'
        ]);

        HoSoTuyenSinh::create([
            'MaHoSo' => 'HS' . time(),
            'MaSV' => $request->MaSV,
            'MaTS' => $request->MaTS,
            'NgayNopHS' => $request->NgayNopHS,
            'TrangThaiHS' => 'DaNop'
        ]);

        return redirect()->back()->with('success', 'Đã tạo hồ sơ thành công');
    }

    public function capNhatTrangThai(Request $request, $maHoSo)
    {
        $request->validate([
            'TrangThaiHS' => 'required|in:DaNop,DaXet,DaTrungTuyen,KhongTrungTuyen'
        ]);

        $hoSo = HoSoTuyenSinh::findOrFail($maHoSo);
        $hoSo->update([
            'TrangThaiHS' => $request->TrangThaiHS
        ]);

        return redirect()->back()->with('success', 'Đã cập nhật trạng thái hồ sơ');
    }
}