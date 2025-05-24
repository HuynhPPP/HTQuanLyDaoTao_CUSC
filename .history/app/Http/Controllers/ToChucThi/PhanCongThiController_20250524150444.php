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

         // Lấy danh sách phân công và kèm thông tin lịch thi + cán bộ
    $phanCongList = PhieuPhanCongThi::with(['lichThi.monHoc', 'canBo'])->get();

        return view('tochucthi.phancongthi.create', compact('lichThis', 'canBos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'MaCB' => 'required|array',
            'MaCB.*' => 'required|exists:canbo,MaCB',
            'VaiTro' => 'required|in:Cán bộ coi thi,Giám sát,Chấm thi',
            'MaLichThi' => 'required|exists:lichthi,MaLichThi',
        ]);

        foreach ($request->MaCB as $maCB) {
            // Kiểm tra xem đã có phân công chưa
            $exists = PhieuPhanCongThi::where('MaLichThi', $request->MaLichThi)
                ->where('MaCB', $maCB)
                ->exists();

            if (!$exists) {
                PhieuPhanCongThi::create([
                    'MaLichThi' => $request->MaLichThi,
                    'MaCB' => $maCB,
                    'VaiTro' => $request->VaiTro,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Đã phân công cán bộ thành công.');
    }

}
