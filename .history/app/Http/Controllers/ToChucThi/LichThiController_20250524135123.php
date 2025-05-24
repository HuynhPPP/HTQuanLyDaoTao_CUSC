<?php

namespace App\Http\Controllers\ToChucThi;

use App\Http\Controllers\Controller;
use App\Models\LichThi;
use App\Models\MonHoc;
use App\Models\LopHoc;
use App\Models\PhongHoc;
use Illuminate\Support\Str;
use Carbon\Carbon;
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
            'TenMH' => 'required|string',
            'MaLop' => 'required|string',
            'NgayThi' => 'required|date|after_or_equal:today',
            'GioBatDau' => 'required',
            'ThoiLuong' => 'required|integer|min:30|max:180',
            'HinhThucThi' => 'required|in:Tự luận,Trắc nghiệm,Bài tập lớn,Thực hành',
            'PhongThi' => 'required|string|max:20',
            'GhiChu' => 'nullable|string',
        ]);

        // Tạo mã lịch thi tự động
        $validated['MaLichThi'] = 'LT' . now()->format('YmdHis') . Str::random(4);

        // Tính khung giờ
        $gioKetThuc = Carbon::createFromFormat('H:i', $validated['GioBatDau'])
            ->addMinutes($validated['ThoiLuong'])
            ->format('H:i');
        $validated['KhungGio'] = $validated['GioBatDau'] . ' - ' . $gioKetThuc;

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
