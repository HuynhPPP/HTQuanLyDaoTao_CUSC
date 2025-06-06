<?php

namespace App\Http\Controllers\GiaoVien;

use App\Http\Controllers\Controller;
use App\Models\GiaoVien;
use App\Models\GiangDay;
use App\Models\DiemThi;
use App\Models\LichThi;
use App\Models\SinhVien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NhapDiemController extends Controller
{
    public function danhSachLopDay()
    {
        // Lấy thông tin giảng viên hiện tại
        $id = session('id');
        $giaoVien = GiaoVien::where('MaGV', $id)->first();

        if (!$giaoVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên');
        }

        // Lấy danh sách môn học giảng viên đang giảng dạy
        $monHocs = GiangDay::with('monHoc')
            ->where('MaGV', $giaoVien->MaGV)
            ->get()
            ->pluck('monHoc', 'lopHoc') // ❌ Không nên dùng ở đây
            ->unique('MaMH');


        return view('giaovien.nhapdiemthi.danh-sach-mon', compact('monHocs'));
    }

    public function danhSachLichThi($tenMH)
    {
        // Lấy thông tin giảng viên hiện tại
        $id = session('id');
        $giaoVien = GiaoVien::where('MaGV', $id)->first();

        if (!$giaoVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên');
        }

        // Lấy danh sách lịch thi cho môn học này
        $lichThis = LichThi::where('TenMH', $tenMH)->get();

        return view('giaovien.nhapdiemthi.danh-sach-lichthi', compact('lichThis', 'tenMH'));
    }

    public function nhapDiem($maLichThi)
    {
        // Lấy thông tin giảng viên hiện tại
        $id = session('id');
        $giaoVien = GiaoVien::where('MaGV', $id)->first();

        if (!$giaoVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên');
        }

        // Lấy thông tin lịch thi
        $lichThi = LichThi::findOrFail($maLichThi);

        // Lấy danh sách sinh viên dự thi
        $sinhViens = SinhVien::whereHas('sinhVienDuThi', function ($query) use ($maLichThi) {
            $query->where('MaLichThi', $maLichThi);
        })->get();

        // Lấy điểm thi hiện tại (nếu có)
        $diemThis = DiemThi::where('MaLichThi', $maLichThi)
            ->get()
            ->keyBy('MaSV');

        return view('giaovien.nhapdiemthi.nhap-diem', compact('sinhViens', 'diemThis', 'lichThi'));
    }

    public function luuDiem(Request $request, $maLichThi)
    {
        // Lấy thông tin giảng viên hiện tại
        $giaoVien = GiaoVien::where('email', Auth::user()->email)->first();

        if (!$giaoVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên');
        }

        // Lấy thông tin lịch thi
        $lichThi = LichThi::findOrFail($maLichThi);

        // Validate dữ liệu
        $request->validate([
            'diems' => 'required|array',
            'diems.*.Diem' => 'nullable|numeric|min:0|max:10',
            'diems.*.DiemThucHanh' => 'nullable|numeric|min:0|max:10',
            'diems.*.DiemLyThuyet' => 'nullable|numeric|min:0|max:10',
            'diems.*.DiemBaiTap' => 'nullable|numeric|min:0|max:10',
        ]);

        // Bắt đầu transaction
        DB::beginTransaction();
        try {
            foreach ($request->diems as $maSV => $diem) {
                // Tìm hoặc tạo mới bản ghi điểm
                $diemThi = DiemThi::firstOrNew([
                    'MaSV' => $maSV,
                    'TenMH' => $lichThi->TenMH,
                    'MaLichThi' => $maLichThi,
                    'LanThi' => $diem['LanThi'] ?? 1
                ]);

                // Cập nhật điểm
                $diemThi->Diem = $diem['Diem'] ?? null;
                $diemThi->DiemThucHanh = $diem['DiemThucHanh'] ?? null;
                $diemThi->DiemLyThuyet = $diem['DiemLyThuyet'] ?? null;
                $diemThi->DiemBaiTap = $diem['DiemBaiTap'] ?? null;
                $diemThi->GhiChu = $diem['GhiChu'] ?? null;

                $diemThi->save();
            }

            // Commit transaction
            DB::commit();

            return redirect()
                ->route('giaovien.nhapdiemthi.nhap-diem', ['maLichThi' => $maLichThi])
                ->with('success', 'Đã lưu điểm thành công');
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi lưu điểm: ' . $e->getMessage());
        }
    }
}