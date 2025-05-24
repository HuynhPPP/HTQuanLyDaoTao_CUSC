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
        ]);

        LichThi::create($validated);
        return redirect()->route('lichthi.index')->with('success', 'Tạo lịch thi thành công');
    }
}
