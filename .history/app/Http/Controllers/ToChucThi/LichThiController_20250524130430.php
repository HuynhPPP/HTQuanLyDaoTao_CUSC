<?php

namespace App\Http\Controllers\ToChucThi;

use App\Http\Controllers\Controller;
use App\Models\LichThi;
use App\Models\MonHoc;
use App\Models\LopHoc;
use App\Models\PhongHoc;
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
            'TenMH' => 'required',
            'MaLop' => 'required',
            'NgayThi' => 'required|date',
            'GioBatDau' => 'required',
            'ThoiLuong' => 'required|integer',
            'HinhThucThi' => 'required',
            'PhongThi' => 'required'
        ], [
            'TenMH.required' => 'Vui lòng chọn môn học',
            'MaLop.required' => 'Vui lòng chọn lớp',
            'NgayThi.required' => 'Vui lòng chọn ngày thi',
            'NgayThi.date' => 'Ngày thi không hợp lệ',
            'GioBatDau.required' => 'Vui lòng chọn giờ bắt đầu',
            'ThoiLuong.required' => 'Vui lòng nhập thời lượng thi',
            'ThoiLuong.integer' => 'Thời lượng thi phải là số nguyên',
            'HinhThucThi.required' => 'Vui lòng chọn hình thức thi',
            'PhongThi.required' => 'Vui lòng chọn phòng thi'
        ]);

        LichThi::create($validated);
        return redirect()->route('lichthi.index')->with('success', 'Tạo lịch thi thành công');
    }
}
