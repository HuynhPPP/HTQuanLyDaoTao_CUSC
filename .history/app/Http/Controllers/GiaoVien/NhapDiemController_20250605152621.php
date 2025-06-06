<?php

namespace App\Http\Controllers\GiaoVien;

use App\Http\Controllers\Controller;
use App\Models\GiaoVien;
use App\Models\GiangDay;
use App\Models\DiemThi;
use App\Models\LopHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NhapDiemController extends Controller
{
    public function danhSachLopDay()
    {
        // Lấy thông tin giảng viên hiện tại
        $giaoVien = GiaoVien::where('email', Auth::user()->email)->first();
        
        if (!$giaoVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên');
        }

        // Lấy danh sách lớp học mà giảng viên đang giảng dạy
        $lopHocs = GiangDay::with(['lopHoc', 'monHoc'])
            ->where('MaGV', $giaoVien->MaGV)
            ->get()
            ->map(function($giangDay) {
                return [
                    'maLop' => $giangDay->lopHoc->MaLop,
                    'tenLop' => $giangDay->lopHoc->TenLop,
                    'maMH' => $giangDay->monHoc->MaMH,
                    'tenMH' => $giangDay->monHoc->TenMH
                ];
            })
            ->unique('maLop');

        return view('giaovien.nhapdiemthi.danh-sach-lop', compact('lopHocs'));
    }

    public function nhapDiem($maLop, $maMH)
    {
        // Lấy thông tin giảng viên hiện tại
        $giaoVien = GiaoVien::where('email', Auth::user()->email)->first();
        
        if (!$giaoVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên');
        }

        // Kiểm tra xem giảng viên có giảng dạy lớp này không
        $giangDay = GiangDay::where('MaGV', $giaoVien->MaGV)
            ->where('MaLop', $maLop)
            ->where('MaMH', $maMH)
            ->first();

        if (!$giangDay) {
            return redirect()->back()->with('error', 'Bạn không được phép nhập điểm cho lớp này');
        }

        // Lấy danh sách sinh viên trong lớp
        $sinhViens = LopHoc::findOrFail($maLop)
            ->sinhViens()
            ->select('sinhvien.MaSV', 'sinhvien.HoTenSV')
            ->get();

        // Lấy điểm thi hiện tại (nếu có)
        $diemThis = DiemThi::where('MaLop', $maLop)
            ->where('MaMH', $maMH)
            ->get()
            ->keyBy('MaSV');

        return view('giaovien.nhapdiemthi.nhap-diem', compact('sinhViens', 'diemThis', 'maLop', 'maMH'));
    }

    public function luuDiem(Request $request, $maLop, $maMH)
    {
        // Lấy thông tin giảng viên hiện tại
        $giaoVien = GiaoVien::where('email', Auth::user()->email)->first();
        
        if (!$giaoVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên');
        }

        // Kiểm tra xem giảng viên có giảng dạy lớp này không
        $giangDay = GiangDay::where('MaGV', $giaoVien->MaGV)
            ->where('MaLop', $maLop)
            ->where('MaMH', $maMH)
            ->first();

        if (!$giangDay) {
            return redirect()->back()->with('error', 'Bạn không được phép nhập điểm cho lớp này');
        }

        // Validate dữ liệu
        $request->validate([
            'diems' => 'required|array',
            'diems.*.DiemCC' => 'nullable|numeric|min:0|max:10',
            'diems.*.DiemGK' => 'nullable|numeric|min:0|max:10',
            'diems.*.DiemCK' => 'nullable|numeric|min:0|max:10',
        ]);

        // Bắt đầu transaction
        DB::beginTransaction();
        try {
            foreach ($request->diems as $maSV => $diem) {
                // Tìm hoặc tạo mới bản ghi điểm
                $diemThi = DiemThi::firstOrNew([
                    'MaSV' => $maSV,
                    'MaLop' => $maLop,
                    'MaMH' => $maMH
                ]);

                // Cập nhật điểm
                $diemThi->DiemCC = $diem['DiemCC'] ?? null;
                $diemThi->DiemGK = $diem['DiemGK'] ?? null;
                $diemThi->DiemCK = $diem['DiemCK'] ?? null;

                // Tính điểm tổng kết nếu có đủ các điểm
                if ($diemThi->DiemCC !== null && $diemThi->DiemGK !== null && $diemThi->DiemCK !== null) {
                    $diemThi->DiemTK = 
                        ($diemThi->DiemCC * 0.1) + 
                        ($diemThi->DiemGK * 0.3) + 
                        ($diemThi->DiemCK * 0.6);
                }

                $diemThi->save();
            }

            // Commit transaction
            DB::commit();

            return redirect()
                ->route('giaovien.nhapdiemthi.nhap-diem', ['maLop' => $maLop, 'maMH' => $maMH])
                ->with('success', 'Đã lưu điểm thành công');
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi lưu điểm: ' . $e->getMessage());
        }
    }
} 