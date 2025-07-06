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
        
        
        // Lấy danh sách giảng viên
        $giaoViens = GiaoVien::orderBy('HoTenGV')->get();

        // Gộp danh sách cán bộ và giảng viên
        $allCanBos = $giaoViens->concat($giaoViens)->map(function($item) {
            return (object)[
                'MaGV' => $item->MaGV,
                'HoTenGV' => $item->HoTenGV,
                'type' => 'GiaoVien'
            ];
        });

        // Lấy danh sách cán bộ đã được phân công cho lịch thi này
        $phanCongList = PhieuPhanCongThi::with(['giaoVien'])
            ->where('MaLichThi', $maLichThi)
            ->get();

        // Lấy danh sách MaCB đã được phân công
        $assignedCBIds = $phanCongList->pluck('MaGV')->toArray();

        // Lọc ra các cán bộ chưa được phân công
        $availableCanBos = $allCanBos->whereNotIn('MaGV', $assignedCBIds);

        return view(
            'tochucthi.phancongthi.create',
            compact('lichThi', 'availableCanBos', 'phanCongList')
        );
    }

    public function store(Request $request, $maLichThi)
    {
        $request->validate([
            'MaGV' => 'required|array|max:10', // Giới hạn tối đa 10 cán bộ
            'MaGV.*' => 'distinct', // Không cho phép trùng lặp
            'VaiTro' => 'required|in:Cán bộ coi thi,Giám sát,Chấm thi'
        ], [
            'MaGV.max' => 'Chỉ được phân công tối đa 10 cán bộ/giảng viên.',
            'MaGV.*.distinct' => 'Không được chọn trùng cán bộ/giảng viên.'
        ]);

        try {
            // Thêm từng cán bộ vào phân công
            foreach ($request->MaGV as $maCB) {
                // Kiểm tra xem cán bộ đã được phân công chưa
                $existingAssignment = PhieuPhanCongThi::where('MaLichThi', $maLichThi)
                    ->where('MaGV', $maCB)
                    ->exists();

                if (!$existingAssignment) {
                    PhieuPhanCongThi::create([
                        'MaLichThi' => $maLichThi,
                        'MaGV' => $maCB,
                        'VaiTro' => $request->VaiTro
                    ]);
                }
            }

            return redirect()->back()->with('success', 'Phân công thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function destroy($maLichThi, $maPhanCong)
    {
        try {
            // Kiểm tra số lượng cán bộ còn lại
            $remainingAssignments = PhieuPhanCongThi::where('MaLichThi', $maLichThi)->count();
            

            $phanCong = PhieuPhanCongThi::where('MaLichThi', $maLichThi)
                ->where('MaPhanCong', $maPhanCong)
                ->firstOrFail();
            
            $phanCong->delete();

            return redirect()->back()->with('success', 'Hủy phân công thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

}
