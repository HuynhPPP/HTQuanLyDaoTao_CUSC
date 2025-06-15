<?php

namespace App\Http\Controllers\ToChucThi;

use App\Http\Controllers\Controller;
use App\Models\LichThi;
use App\Models\MonHoc;
use App\Models\LopHoc;
use App\Models\PhongHoc;
use App\Models\sinhvien;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DanhSachSinhVienLopExport;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LichThiController extends Controller
{
    public function index()
    {
        $lichThis = LichThi::with(['monHoc'])->get();
        return view('tochucthi.lichthi.index', compact('lichThis'));
    }

    public function create()
    {
        $monHocs = MonHoc::all();
        $lopHocs = LopHoc::all();
        $phongHocs = PhongHoc::all();
        return view('tochucthi.lichthi.create', compact('monHocs', 'lopHocs', 'phongHocs'));
    }

    private function validateLichThi(Request $request)
    {
        return $request->validate([
            'TenMH' => 'required|string',
            'MaLop' => 'required|string',
            'NgayThi' => 'required|date|after_or_equal:today',
            'GioBatDau' => 'required',
            'ThoiLuong' => 'required|integer',
            'HinhThucThi' => 'required',
            'PhongThi' => 'required|string',
            'GhiChu' => 'nullable|string',
            'LanThi' => 'nullable|numeric',
        ], [
            'TenMH.required' => 'Vui lòng chọn môn học.',
            'TenMH.string' => 'Tên môn học không hợp lệ.',

            'MaLop.required' => 'Vui lòng chọn lớp.',
            'MaLop.string' => 'Lớp không hợp lệ.',

            'NgayThi.required' => 'Vui lòng chọn ngày thi.',
            'NgayThi.date' => 'Ngày thi không hợp lệ.',
            'NgayThi.after_or_equal' => 'Ngày thi phải từ hôm nay trở đi.',

            'GioBatDau.required' => 'Vui lòng chọn giờ bắt đầu.',

            'ThoiLuong.required' => 'Vui lòng nhập thời lượng thi.',
            'ThoiLuong.integer' => 'Thời lượng thi phải là số nguyên.',

            'LanThi.numeric' => 'Lần thi phải là số',

            'HinhThucThi.required' => 'Vui lòng chọn hình thức thi.',

            'PhongThi.required' => 'Vui lòng chọn phòng thi.',
            'PhongThi.string' => 'Phòng thi không hợp lệ.',

            'GhiChu.string' => 'Ghi chú không hợp lệ.',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateLichThi($request);

        $validated['MaLichThi'] = 'LT' . now()->format('ymdHi');

        $gioKetThuc = Carbon::createFromFormat('H:i', $validated['GioBatDau'])
            ->addMinutes((int) $validated['ThoiLuong'])
            ->format('H:i');

        $validated['KhungGio'] = $validated['GioBatDau'] . ' - ' . $gioKetThuc;

        LichThi::create($validated);

        return redirect()->route('lichthi.index')->with('success', 'Lập lịch thi thành công');
    }

    public function edit($id)
    {
        $lichThi = LichThi::findOrFail($id);
        $monHocs = MonHoc::all();
        $lopHocs = LopHoc::all();
        $phongHocs = PhongHoc::all();

        [$gioBatDau, $gioKetThuc] = array_map('trim', explode(' - ', $lichThi->KhungGio));
        $carbonBatDau = Carbon::createFromFormat('H:i', $gioBatDau);
        $carbonKetThuc = Carbon::createFromFormat('H:i', $gioKetThuc);

        $thoiLuong = $carbonBatDau->diffInMinutes($carbonKetThuc);

        $lichThi->GioBatDau = $gioBatDau;
        $lichThi->ThoiLuong = $thoiLuong;

        return view('tochucthi.lichthi.edit', compact('lichThi', 'monHocs', 'lopHocs', 'phongHocs'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validateLichThi($request);

        $gioKetThuc = Carbon::createFromFormat('H:i', $validated['GioBatDau'])
            ->addMinutes((int) $validated['ThoiLuong'])
            ->format('H:i');

        $validated['KhungGio'] = $validated['GioBatDau'] . ' - ' . $gioKetThuc;

        $lichThi = LichThi::findOrFail($id);
        $lichThi->update($validated);

        return redirect()->route('lichthi.index')->with('success', 'Cập nhật lịch thi thành công!');
    }

    public function destroy($maLichThi, $maPhanCong)
    {
        try {
            // Kiểm tra số lượng cán bộ còn lại
            $remainingAssignments = PhieuPhanCongThi::where('MaLichThi', $maLichThi)->count();
            
            // Không cho xóa nếu chỉ còn 1 cán bộ
            if ($remainingAssignments <= 1) {
                return redirect()->back()->with('error', 'Phải có ít nhất một cán bộ được phân công.');
            }

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
