<?php

namespace App\Http\Controllers\ToChucThi;

use App\Http\Controllers\Controller;
use App\Models\LichThi;
use App\Models\MonHoc;
use App\Models\LopHoc;
use App\Models\PhongHoc;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class LichThiController extends Controller
{
    public function index()
    {
        $lichThis = LichThi::with(['monHoc'])->get();
        return view('tochucthi.lichthi.index', compact('lichThis'));
    }

    public function create()
    {
        $monHocs = MonHoc::all();
        $lopHocs = LopHoc::all();
        $phongHocs = PhongHoc::all();
        return view('tochucthi.lichthi.create', compact('monHocs', 'lopHocs', 'phongHocs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'TenMH' => 'required|string|max:12',
            'MaLop' => 'required|string|max:12',
            'NgayThi' => 'required|date|after_or_equal:today',
            'KhungGio' => 'required|string|max:100',
            'PhongThi' => 'required|string|max:20',
            'LoaiThi' => 'required|in:Lý thuyết,Thực hành,Bài tập lớn',
            'GhiChu' => 'nullable|string',
        ], [
            'TenMH.required' => 'Vui lòng chọn môn học',
            'MaLop.required' => 'Vui lòng chọn lớp',
            'NgayThi.required' => 'Vui lòng chọn ngày thi',
            'NgayThi.after_or_equal' => 'Ngày thi không thể là ngày trong quá khứ',
            'KhungGio.required' => 'Vui lòng nhập khung giờ thi',
            'PhongThi.required' => 'Vui lòng chọn phòng thi',
            'LoaiThi.required' => 'Vui lòng chọn loại thi',
        ]);

        // Tạo mã lịch thi tự động, ví dụ: LT202505240001
        $validated['MaLichThi'] = 'LT' . now()->format('YmdHis') . Str::random(4);

        LichThi::create($validated);

        return redirect()->route('lichthi.index')->with('success', 'Lập lịch thi thành công');
    }


    public function edit($id)
    {
        $lichThi = LichThi::findOrFail($id);
        $monHocs = MonHoc::all();
        $lopHocs = LopHoc::all();
        $phongHocs = PhongHoc::all();

        return view('tochucthi.lichthi.edit', compact('lichThi', 'monHocs', 'lopHocs', 'phongHocs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'TenMH' => 'required|string',
            'MaLop' => 'required|string',
            'NgayThi' => 'required|date|after_or_equal:today',
            'GioBatDau' => 'required',
            'ThoiLuong' => 'required|integer|min:30|max:180',
            'HinhThucThi' => 'required|string|in:Tự Luận,Trắc Nghiệm,Vấn Đáp,Thực Hành',
            'PhongThi' => 'required|string',
        ]);

        $lichThi = LichThi::findOrFail($id);

        $lichThi->update([
            'TenMH' => $request->TenMH,
            'MaLop' => $request->MaLop,
            'NgayThi' => $request->NgayThi,
            'GioBatDau' => $request->GioBatDau,
            'ThoiLuong' => $request->ThoiLuong,
            'HinhThucThi' => $request->HinhThucThi,
            'PhongThi' => $request->PhongThi,
        ]);

        return redirect()->route('lichthi.index')->with('success', 'Cập nhật lịch thi thành công!');
    }

}
