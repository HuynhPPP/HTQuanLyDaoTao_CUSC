<?php

namespace App\Http\Controllers\ToChucThi;

use App\Http\Controllers\Controller;
use App\Models\CanBo;
use App\Models\GiaoVien;
use App\Models\LichThi;
use App\Models\PhieuPhanCongThi;
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
        
        // Lấy danh sách cán bộ
        $canBos = CanBo::orderBy('HoTenCB')->get();
        
        // Lấy danh sách giảng viên
        $giaoViens = GiaoVien::orderBy('HoTenGV')->get();

        // Gộp danh sách cán bộ và giảng viên
        $allCanBos = $canBos->concat($giaoViens)->map(function($item) {
            return (object)[
                'MaCB' => $item->MaCB ?? $item->MaGV,
                'HoTenCB' => $item->HoTenCB ?? $item->HoTenGV,
                'type' => $item->MaCB ? 'CanBo' : 'GiaoVien'
            ];
        });

        // Lấy danh sách cán bộ đã được phân công cho lịch thi này
        $phanCongList = PhieuPhanCongThi::with(['canBo', 'giaoVien'])
            ->where('MaLichThi', $maLichThi)
            ->get();

        // Lấy danh sách MaCB đã được phân công
        $assignedCBIds = $phanCongList->pluck('MaCB')->toArray();

        // Lọc ra các cán bộ chưa được phân công
        $availableCanBos = $allCanBos->whereNotIn('MaCB', $assignedCBIds);

        return view(
            'tochucthi.phancongthi.create',
            compact('lichThi', 'availableCanBos', 'phanCongList')
        );
    }

    public function store(Request $request, $maLichThi)
    {
        $request->validate([
            'MaCB' => 'required|array',
            'VaiTro' => 'required|in:Cán bộ coi thi,Giám sát,Chấm thi'
        ]);

        try {
            // Thêm từng cán bộ vào phân công
            foreach ($request->MaCB as $maCB) {
                PhieuPhanCongThi::create([
                    'MaLichThi' => $maLichThi,
                    'MaCB' => $maCB,
                    'VaiTro' => $request->VaiTro
                ]);
            }

            return redirect()->back()->with('success', 'Phân công thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy($maLichThi, $maPhanCong)
    {
        try {
            $phanCong = PhieuPhanCongThi::findOrFail($maPhanCong);
            $phanCong->delete();

            return redirect()->back()->with('success', 'Hủy phân công thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

}
