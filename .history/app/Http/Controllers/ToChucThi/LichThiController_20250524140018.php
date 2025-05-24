<?php

namespace App\Http\Controllers\ToChucThi;

use App\Http\Controllers\Controller;
use App\Models\LichThi;
use App\Models\MonHoc;
use App\Models\LopHoc;
use App\Models\PhongHoc;
use Illuminate\Support\Str;
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
            'ThoiLuong' => 'required|integer|min:30|max:180',
            'HinhThucThi' => 'required',
            'PhongThi' => 'required|string',
            'GhiChu' => 'nullable|string',
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
            'ThoiLuong.min' => 'Thời lượng tối thiểu là 30 phút.',
            'ThoiLuong.max' => 'Thời lượng tối đa là 180 phút.',

            'HinhThucThi.required' => 'Vui lòng chọn hình thức thi.',

            'PhongThi.required' => 'Vui lòng chọn phòng thi.',
            'PhongThi.string' => 'Phòng thi không hợp lệ.',

            'GhiChu.string' => 'Ghi chú không hợp lệ.',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateLichThi($request);

        $validated['MaLichThi'] = 'LT' . now()->format('YmdHis') . Str::random(4);

        $gioKetThuc = Carbon::createFromFormat('H:i', $validated['GioBatDau'])
    ->addMinutes((int) $validated['ThoiLuong']) // ép kiểu về int
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

        return view('tochucthi.lichthi.edit', compact('lichThi', 'monHocs', 'lopHocs', 'phongHocs'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validateLichThi($request);

        $gioKetThuc = Carbon::createFromFormat('H:i', $validated['GioBatDau'])
            ->addMinutes($validated['ThoiLuong'])
            ->format('H:i');

        $validated['KhungGio'] = $validated['GioBatDau'] . ' - ' . $gioKetThuc;

        $lichThi = LichThi::findOrFail($id);
        $lichThi->update($validated);

        return redirect()->route('lichthi.index')->with('success', 'Cập nhật lịch thi thành công!');
    }


}
