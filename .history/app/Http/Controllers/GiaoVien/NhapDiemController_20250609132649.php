<?php

namespace App\Http\Controllers\GiaoVien;

use App\Http\Controllers\Controller;
use App\Models\GiaoVien;
use App\Models\GiangDay;
use App\Models\DiemThi;
use App\Models\LichThi;
use App\Models\lophoc;
use App\Models\monhoc;
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
        $giangDays = GiangDay::with(['monHoc', 'lopHoc'])
            ->where('MaGV', $giaoVien->MaGV)
            ->get();

        return view('giaovien.nhapdiemthi.danh-sach-mon', compact('giangDays'));

    }

    public function danhSachLichThi($MaMH)
    {
        // Lấy thông tin giảng viên hiện tại
        $id = session('id');
        $giaoVien = GiaoVien::where('MaGV', $id)->first();

        if (!$giaoVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên');
        }

        // Lấy danh sách lịch thi cho môn học này
        $lichThis = LichThi::where('MaMH', $MaMH)->get();
        $TenMH = optional($lichThis->first()->monhoc)->TenMH;


        return view('giaovien.nhapdiemthi.danh-sach-lichthi', compact('lichThis', 'MaMH', 'TenMH'));
    }

    public function nhapDiem($MaLop)
    {
        // Lấy thông tin giảng viên hiện tại
        $id = session('id');
        $giaoVien = GiaoVien::where('MaGV', $id)->first();

        if (!$giaoVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên');
        }

        // Lấy thông tin lớp học
        $lop = lophoc::where('MaLop', $MaLop)->firstOrFail();
        $lophoc = $lop->TenLop;

        // Lấy thông tin môn học từ lớp học
        $monHoc = monhoc::whereHas('giangday', function($query) use ($MaLop) {
            $query->where('MaLop', $MaLop);
        })->first();

        // Lấy danh sách sinh viên dự thi từ bảng sinhvien_duthi
        $sinhViens = DB::table('sinhvien_duthi')
            ->where('MaLop', $MaLop)
            ->join('sinhvien', 'sinhvien_duthi.MaSV', '=', 'sinhvien.MaSV')
            ->select(
                'sinhvien.MaSV', 
                'sinhvien.HoTen', 
                'sinhvien_duthi.TrangThaiDuThi',
                'sinhvien_duthi.GhiChu'
            )
            ->distinct('sinhvien.MaSV')
            ->get();

        // Lấy điểm thi hiện tại (nếu có)
        $diemThis = DiemThi::where('MaLop', $MaLop)
            ->where('MaMH', $monHoc->MaMH ?? null)
            ->get()
            ->keyBy('MaSV');

        return view('giaovien.nhapdiemthi.nhap-diem', compact(
            'sinhViens', 
            'diemThis', 
            'lophoc', 
            'lop', 
            'monHoc'
        ));
    }

    public function luuDiem(Request $request, $lophoc)
    {
        // Lấy thông tin giảng viên hiện tại
        $id = session('id');
        $giaoVien = GiaoVien::where('MaGV', $id)->first();

        if (!$giaoVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên');
        }

        // Lấy thông tin lớp học
        $lop = LopHoc::where('MaLop', $lophoc)->firstOrFail();

        // Validate dữ liệu
        $request->validate([
            'diems' => 'required|array',
            'diems.*.Diem' => 'nullable|numeric|min:0|max:100',
            'diems.*.DiemThucHanh' => 'nullable|numeric|min:0|max:100',
            'diems.*.DiemLyThuyet' => 'nullable|numeric|min:0|max:100',
            'diems.*.DiemBaiTap' => 'nullable|numeric|min:0|max:10',
            'diems.*.GhiChu' => 'nullable|string|max:255',
        ]);

        // Lấy thông tin môn học từ lớp học
        $monHoc = MonHoc::findOrFail($lop->MaMH);

        // Bắt đầu transaction
        DB::beginTransaction();
        try {
            foreach ($request->diems as $maSV => $diem) {
                // Tìm hoặc tạo mới bản ghi điểm
                $diemThi = DiemThi::firstOrNew([
                    'MaSV' => $maSV,
                    'MaMH' => $monHoc->MaMH,
                    'MaLop' => $lophoc,
                ]);

                // Cập nhật điểm
                $diemThi->DiemTong = $diem['Diem'] ?? null;
                $diemThi->DiemThucHanh = $diem['DiemThucHanh'] ?? null;
                $diemThi->DiemLyThuyet = $diem['DiemLyThuyet'] ?? null;
                $diemThi->DiemDuAn = $diem['DiemBaiTap'] ?? null; // Mapping DiemBaiTap to DiemDuAn
                $diemThi->GhiChu = $diem['GhiChu'] ?? null;

                $diemThi->save();
            }

            // Commit transaction
            DB::commit();

            return redirect()
                ->route('giaovien.nhapdiemthi.nhap-diem', ['lophoc' => $lophoc])
                ->with('success', 'Đã lưu điểm thành công');
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi lưu điểm: ' . $e->getMessage());
        }
    }
}