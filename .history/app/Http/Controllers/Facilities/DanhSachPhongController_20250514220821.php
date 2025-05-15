<?php

namespace App\Http\Controllers;

use App\Models\danhsachphong;
use App\Models\lophoc;
use App\Models\phonghoc;
use Illuminate\Http\Request;

class DanhSachPhongController extends Controller
{
    public function index()
    {
        $danhsachphongs = danhsachphong::with(['lopHoc', 'phongHoc'])->paginate(10);
        return view('quanly_cosovatchat.danhsachphong.index', compact('danhsachphongs'));
    }

    public function create()
    {
        $lophocs = lophoc::all();
        $phonghocs = phonghoc::all();
        return view('quanly_cosovatchat.danhsachphong.create', compact('lophocs', 'phonghocs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'MaLop' => 'required',
            'TenPhong' => 'required',
        ]);
        danhsachphong::create($request->all());
        return redirect()->route('danhsachphong.index')->with('success', 'Gán phòng cho lớp thành công');
    }

    public function show($maLop)
    {
        $danhsachphong = danhsachphong::where('MaLop', $maLop)->with(['lopHoc', 'phongHoc'])->get();
        return view('quanly_cosovatchat.danhsachphong.show', compact('danhsachphong'));
    }

    public function edit($maLop)
    {
        $danhsachphong = danhsachphong::where('MaLop', $maLop)->firstOrFail();
        $lophocs = lophoc::all();
        $phonghocs = phonghoc::all();
        return view('quanly_cosovatchat.danhsachphong.edit', compact('danhsachphong', 'lophocs', 'phonghocs'));
    }

    public function update(Request $request, $maLop)
    {
        $danhsachphong = danhsachphong::where('MaLop', $maLop)->firstOrFail();
        $danhsachphong->update($request->all());
        return redirect()->route('danhsachphong.index')->with('success', 'Cập nhật gán phòng thành công');
    }

    public function destroy($maLop)
    {
        $danhsachphong = danhsachphong::where('MaLop', $maLop)->firstOrFail();
        $danhsachphong->delete();
        return redirect()->route('danhsachphong.index')->with('success', 'Xóa gán phòng thành công');
    }
} 