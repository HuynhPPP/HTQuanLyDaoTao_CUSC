<?php

namespace App\Http\Controllers\ToChucThi;

use App\Http\Controllers\Controller;
use App\Models\PhieuPhanCongThi;
use App\Models\LichThi;
use App\Models\CanBo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PhanCongThiController extends Controller
{
    public function index()
    {
        $lichThis = LichThi::with('monHoc')->orderBy('NgayThi', 'desc')->get();
        return view('tochucthi.phancongthi.index', compact('lichThis'));
    }

    public function create($maLichThi)
    {
        $lichThi = LichThi::with('monHoc')->findOrFail($maLichThi);
        $canBos = CanBo::orderBy('HoTenCB')->get();

        // Lấy danh sách cán bộ đã được phân công cho lịch thi này
        $phanCongList = PhieuPhanCongThi::with('canBo')
            ->where('MaLichThi', $maLichThi)
            ->get();

        // Lấy danh sách MaCB đã được phân công
        $assignedCBIds = $phanCongList->pluck('MaCB')->toArray();

        // Lọc ra các cán bộ chưa được phân công
        $availableCanBos = $canBos->whereNotIn('MaCB', $assignedCBIds);

        return view(
            'tochucthi.phancongthi.create',
            compact('lichThi', 'availableCanBos', 'phanCongList')
        );
    }

    public function store(Request $request, $maLichThi)
    {
        $request->validate([
            'MaCB' => 'required|array',
            'MaCB.*' => 'required|exists:canbo,MaCB',
            'VaiTro' => 'required|in:Cán bộ coi thi,Giám sát,Chấm thi',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->MaCB as $maCB) {
                // Kiểm tra xem đã có phân công chưa
                $exists = PhieuPhanCongThi::where('MaLichThi', $maLichThi)
                    ->where('MaCB', $maCB)
                    ->exists();

                if (!$exists) {
                    PhieuPhanCongThi::create([
                        'MaLichThi' => $maLichThi,
                        'MaCB' => $maCB,
                        'VaiTro' => $request->VaiTro,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('phancong.create', $maLichThi)
                ->with('success', 'Phân công cán bộ thành công.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi phân công cán bộ.');
        }
    }

    public function destroy($maLichThi, $maPhanCong)
    {
        try {
            $phanCong = PhieuPhanCongThi::findOrFail($maPhanCong);
            $phanCong->delete();

            return redirect()->route('phancong.create', $maLichThi)
                ->with('success', 'Đã hủy phân công thành công.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi hủy phân công.');
        }
    }

}
