<?php

namespace App\Http\Controllers\ToChucThi;

use App\Http\Controllers\Controller;
use App\Models\PhieuPhanCongThi;
use App\Models\LichThi;
use App\Models\CanBo;
use Illuminate\Http\Request;

class PhanCongThiController extends Controller
{
    public function index($maLichThi)
    {
        $lichThi = LichThi::with('monHoc')->findOrFail($maLichThi);
        $phanCongs = PhieuPhanCongThi::where('MaLichThi', $maLichThi)->with('canBo')->get();
        $canBos = CanBo::all();

        return view('tochucthi.phancongthi.index', compact('lichThi', 'phanCongs', 'canBos'));
    }

    // Lưu phân công mới cho lịch thi
    public function store(Request $request, $maLichThi)
    {
        $request->validate([
            'MaCB' => 'required|exists:canbo,id',
            'VaiTro' => 'required|in:Cán bộ coi thi,Giám sát,Chấm thi',
        ]);

        // Kiểm tra tránh phân công trùng cán bộ
        $exists = PhieuPhanCongThi::where('MaLichThi', $maLichThi)
            ->where('MaCB', $request->MaCB)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Cán bộ này đã được phân công cho lịch thi này.');
        }

        PhieuPhanCongThi::create([
            'MaLichThi' => $maLichThi,
            'MaCB' => $request->MaCB,
            'VaiTro' => $request->VaiTro,
        ]);

        return redirect()->back()->with('success', 'Phân công thành công.');
    }
}
