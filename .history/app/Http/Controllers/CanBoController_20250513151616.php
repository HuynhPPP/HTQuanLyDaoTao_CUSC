<?php

namespace App\Http\Controllers;

use App\Models\CanBo;
use App\Models\HocVi;
use App\Models\BangCapCanBo;
use App\Models\ChucVu;
use App\Models\DonVi;
use App\Models\TapHuan;
use Illuminate\Http\Request;

class CanBoController extends Controller
{
    public function index() {
        $canbos = CanBo::with(['hocvi', 'bangcap', 'chucvu', 'donvi', 'taphuan'])->paginate(10);
        return view('canbo.index', compact('canbos'));
    }

    public function create() {
        $hocvis = HocVi::all();
        $bangcaps = BangCapCanBo::all();
        $chucvus = ChucVu::all();
        $donvis = DonVi::all();
        $taphuans = TapHuan::all();
        return view('canbo.create', compact('hocvis', 'bangcaps', 'chucvus', 'donvis', 'taphuans'));
    }

    public function store(Request $request) {
        $request->validate([
            'MaCB' => 'required|unique:canbo,MaCB',
            'HoTenCB' => 'required',
            'GioiTinh' => 'required',
            'Email' => 'required|email',
            'Sdt' => 'required',
            // ... các trường khác
        ]);
        CanBo::create($request->all());
        return redirect()->route('canbo.index')->with('success', 'Thêm cán bộ thành công');
    }

    public function show($maCB) {
        $canbo = CanBo::with(['hocvi', 'bangcap', 'chucvu', 'donvi', 'taphuan'])->where('MaCB', $maCB)->firstOrFail();
        return view('canbo.show', compact('canbo'));
    }

    public function edit($maCB) {
        $canbo = CanBo::where('MaCB', $maCB)->firstOrFail();
        // Lấy danh sách các bảng phụ như create
        return view('canbo.edit', compact('canbo', ...));
    }

    public function update(Request $request, $maCB) {
        $canbo = CanBo::where('MaCB', $maCB)->firstOrFail();
        $canbo->update($request->all());
        return redirect()->route('canbo.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy($maCB) {
        $canbo = CanBo::where('MaCB', $maCB)->firstOrFail();
        $canbo->delete();
        return redirect()->route('canbo.index')->with('success', 'Xóa thành công');
    }
};
