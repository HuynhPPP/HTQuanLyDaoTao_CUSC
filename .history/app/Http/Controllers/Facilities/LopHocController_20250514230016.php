<?php

namespace App\Http\Controllers\Facilities;
use App\Http\Controllers\Controller;
use App\Models\lophoc;
use Illuminate\Http\Request;

class LopHocController extends Controller
{
    public function index()
    {
        $lophocs = lophoc::paginate(10);
        return view('quanly_cosovatchat.lophoc.index', compact('lophocs'));
    }

    public function create()
    {
        return view('quanly_cosovatchat.lophoc.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'MaLop' => 'required|unique:lophoc,MaLop',
            'TenLop' => 'required',
        ]);
        lophoc::create($request->all());
        return redirect()->route('lophoc.index')->with('success', 'Thêm lớp học thành công');
    }

    public function show($maLop)
    {
        $lophoc = lophoc::findOrFail($maLop);
        return view('quanly_cosovatchat.lophoc.show', compact('lophoc'));
    }

    public function edit($maLop)
    {
        $lophoc = lophoc::findOrFail($maLop);
        $lophoc = lophoc::where('MaLop', $maLop)->firstOrFail();
        return view('quanly_cosovatchat.lophoc.edit', compact('lophoc'));
    }

    public function update(Request $request, $maLop)
    {
        $lophoc = lophoc::findOrFail($maLop);
        $lophoc->update($request->all());
        return redirect()->route('lophoc.index')->with('success', 'Cập nhật lớp học thành công');
    }

    public function destroy($maLop)
    {
        $lophoc = lophoc::findOrFail($maLop);
        $lophoc->delete();
        return redirect()->route('lophoc.index')->with('success', 'Xóa lớp học thành công');
    }
} 