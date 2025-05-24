<?php

namespace App\Http\Controllers\ToChucThi;

use App\Http\Controllers\Controller;
use App\Models\PhieuPhanCongThi;
use App\Models\LichThi;
use App\Models\CanBo;
use Illuminate\Http\Request;

class PhanCongThiController extends Controller
{
    public function create()
    {
        $lichThis = LichThi::all();
        $canBos = CanBo::all();

        return view('tochucthi.phancongthi.create', compact('lichThis', 'canBos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'MaLichThi' => 'required|exists:lichthi,id',
            'MaCB' => 'required|exists:canbo,id',
            'VaiTro' => 'required|in:Cán bộ coi thi,Giám sát,Chấm thi',
        ]);

        PhieuPhanCongThi::create($request->only('MaLichThi', 'MaCB', 'VaiTro'));

        return redirect()->back()->with('success', 'Phân công cán bộ thành công!');
    }

    public function index()
    {
        $phanCongs = PhieuPhanCongThi::with(['lichThi', 'canBo'])->get();
        return view('tochucthi.phancongthi.index', compact('phanCongs'));
    }
}
