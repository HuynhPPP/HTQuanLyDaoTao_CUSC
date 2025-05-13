<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\bangcapcanbo;
use Illuminate\Http\Request;

class BangCapCanBoController extends Controller
{
    public function index() {
        $bangcaps = bangcapcanbo::paginate(10);
        return view('quanly_canbo.bangcapcanbo.index', compact('bangcaps'));
    }

    public function create() {
        return view('quanly_canbo.bangcapcanbo.create');
    }

    public function store(Request $request) {
        $request->validate([
            'MaBang' => 'required|unique:bangcapcanbo,MaBang',
            'TenBang' => 'required',
            'ThoiGianCap' => 'required|date',
            'DonViCap' => 'required',
        ]);
        bangcapcanbo::create($request->all());
        return redirect()->route('bangcapcanbo.index')->with('success', 'Thêm bằng cấp thành công');
    }

    public function show($maBang) {
        $bangcap = bangcapcanbo::findOrFail($maBang);
        return view('quanly_canbo.bangcapcanbo.show', compact('bangcap'));
    }

    public function edit($maBang) {
        $bangcap = bangcapcanbo::findOrFail($maBang);
        return view('bangcapcanbo.edit', compact('bangcap'));
    }

    public function update(Request $request, $maBang) {
        $bangcap = bangcapcanbo::findOrFail($maBang);
        $bangcap->update($request->all());
        return redirect()->route('bangcapcanbo.index')->with('success', 'Cập nhật bằng cấp thành công');
    }

    public function destroy($maBang) {
        $bangcap = bangcapcanbo::findOrFail($maBang);
        $bangcap->delete();
        return redirect()->route('bangcapcanbo.index')->with('success', 'Xóa bằng cấp thành công');
    }
}
