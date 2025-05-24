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
        $lichThis = LichThi::with('monHoc')->get();
        $canBos = CanBo::all();

        return view('tochucthi.phancong.create', compact('lichThis', 'canBos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'MaCB' => 'required|exists:canbo,id',
            'VaiTro' => 'required|in:Cán bộ coi thi,Giám sát,Chấm thi',
            'MaLichThi' => 'required|exists:lichthi,id',
        ]);

        // Kiểm tra tránh trùng
        $exists = PhieuPhanCongThi::where('MaLichThi', $request->MaLichThi)
            ->where('MaCB', $request->MaCB)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Cán bộ đã được phân công cho lịch thi này.');
        }

        PhieuPhanCongThi::create([
            'MaLichThi' => $request->MaLichThi,
            'MaCB' => $request->MaCB,
            'VaiTro' => $request->VaiTro,
        ]);

        return redirect()->back()->with('success', 'Phân công thành công!');
    }
}
