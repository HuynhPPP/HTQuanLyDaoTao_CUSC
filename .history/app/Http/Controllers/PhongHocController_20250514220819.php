<?php

namespace App\Http\Controllers;

use App\Models\phonghoc;
use Illuminate\Http\Request;

class PhongHocController extends Controller
{
    public function index()
    {
        $phonghocs = phonghoc::paginate(10);
        return view('quanly_cosovatchat.phonghoc.index', compact('phonghocs'));
    }

    public function create()
    {
        return view('quanly_cosovatchat.phonghoc.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'TenPhong' => 'required|unique:phonghoc,TenPhong',
            'LoaiPhong' => 'required',
        ]);
        phonghoc::create($request->all());
        return redirect()->route('phonghoc.index')->with('success', 'Thêm phòng học thành công');
    }

    public function show($tenPhong)
    {
        $phonghoc = phonghoc::findOrFail($tenPhong);
        return view('quanly_cosovatchat.phonghoc.show', compact('phonghoc'));
    }

    public function edit($tenPhong)
    {
        $phonghoc = phonghoc::findOrFail($tenPhong);
        return view('quanly_cosovatchat.phonghoc.edit', compact('phonghoc'));
    }

    public function update(Request $request, $tenPhong)
    {
        $phonghoc = phonghoc::findOrFail($tenPhong);
        $phonghoc->update($request->all());
        return redirect()->route('phonghoc.index')->with('success', 'Cập nhật phòng học thành công');
    }

    public function destroy($tenPhong)
    {
        $phonghoc = phonghoc::findOrFail($tenPhong);
        $phonghoc->delete();
        return redirect()->route('phonghoc.index')->with('success', 'Xóa phòng học thành công');
    }
} 