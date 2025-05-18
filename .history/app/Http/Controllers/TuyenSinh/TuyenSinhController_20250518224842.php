<?php

namespace App\Http\Controllers\TuyenSinh;

use App\Http\Controllers\Controller;
use App\Models\HoSoTuyenSinh;
use App\Models\ThongTinTuyenSinh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $dotTuyenSinh = ThongTinTuyenSinh::findOrFail($maTS);
        $hoSo = HoSoTuyenSinh::with('sinhvien')
            ->where('MaTS', $maTS)
            ->get();
        return view('quanly_tuyensinh.tuyensinh.danhsach_hoso', compact('hoSo', 'dotTuyenSinh'));
    }

    public function taoHoSo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'MaSV' => 'required|exists:SinhVien,MaSV',
            'MaTS' => 'required',
            'NgayNopHS' => 'required|date'
        ], [
            'MaSV.required' => 'Vui lòng nhập mã sinh viên',
            'MaSV.exists' => 'Mã sinh viên không tồn tại trong hệ thống',
            'MaTS.required' => 'Vui lòng chọn đợt tuyển sinh',
            'NgayNopHS.required' => 'Vui lòng chọn ngày nộp hồ sơ',
            'NgayNopHS.date' => 'Ngày nộp hồ sơ không hợp lệ'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Tạo hồ sơ không thành công !');
        }

        try {
            HoSoTuyenSinh::create([
                'MaHoSo' => 'HS' . time(),
                'MaSV' => $request->MaSV,
                'MaTS' => $request->MaTS,
                'NgayNopHS' => $request->NgayNopHS,
                'TrangThaiHS' => 'DaNop'
            ]);

            return redirect()->back()->with('success', 'Đã tạo hồ sơ thành công');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Tạo hồ sơ không thành công! ' . $e->getMessage());
        }
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'NamTS' => 'required|integer|min:2000|max:2100',
            'DotTS' => 'required|integer|min:1|max:4',
            'NgayBatDau' => 'required|date',
            'NgayKetThuc' => 'required|date|after:NgayBatDau',
            'ChiTieuTS' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $maTS = 'TS' . $request->NamTS . $request->DotTS;

        ThongTinTuyenSinh::create([
            'MaTS' => $maTS,
            'NamTS' => $request->NamTS,
            'DotTS' => $request->DotTS,
            'NgayBatDau' => $request->NgayBatDau,
            'NgayKetThuc' => $request->NgayKetThuc,
            'ChiTieuTS' => $request->ChiTieuTS
        ]);

        return redirect()->back()->with('success', 'Thêm đợt tuyển sinh thành công');
    }

    public function destroy($maTS)
    {
        $dotTS = ThongTinTuyenSinh::findOrFail($maTS);
        $dotTS->delete();
        return redirect()->back()->with('success', 'Xóa đợt tuyển sinh thành công');
    }
}