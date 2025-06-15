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
use App\Models\HinhThucDanhGia;
use App\Models\TieuChiXepLoai;
use App\Models\ChuongTrinhDaoTao;
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
        $monHoc = monhoc::whereHas('giangday', function ($query) use ($MaLop) {
            $query->where('MaLop', $MaLop);
        })->first();

        // Lấy danh sách sinh viên dự thi từ bảng sinhvien_duthi
        $sinhViens = DB::table('sinhvien_duthi')
            ->where('MaLop', $MaLop)
            ->where('TrangThaiDuThi', 'DuThi')
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

    public function luuDiem(Request $request, $MaLop)
    {
        // Extensive debugging
        \Log::info('Luu Diem Request - Detailed', [
            'MaLop' => $MaLop,
            'full_url' => request()->fullUrl(),
            'method' => request()->method(),
            'route_name' => request()->route()->getName(),
            'route_parameters' => request()->route()->parameters(),
            'input' => $request->all(),
            'server_params' => request()->server(),
            'headers' => request()->headers->all(),
            'middleware' => request()->route()->middleware(),
            'current_user' => auth()->user() ? auth()->user()->toArray() : 'Not authenticated',
        ]);

        // Check if the route is actually matched
        $currentRoute = request()->route();
        if (!$currentRoute) {
            \Log::error('No route matched for the current request');
            abort(404, 'Route not found');
        }

        // Validate route parameters
        if (!$MaLop) {
            \Log::error('No MaLop parameter provided');
            return redirect()->back()->with('error', 'Mã lớp học không hợp lệ');
        }

        // Lấy thông tin giảng viên hiện tại
        $id = session('id');
        $giaoVien = GiaoVien::where('MaGV', $id)->first();

        if (!$giaoVien) {
            \Log::warning('No teacher found for session ID', ['session_id' => $id]);
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên');
        }

        // Lấy thông tin lớp học
        $lop = LopHoc::where('MaLop', $MaLop)->first();
        if (!$lop) {
            \Log::error('Lop not found', ['MaLop' => $MaLop]);
            return redirect()->back()->with('error', 'Không tìm thấy lớp học: ' . $MaLop);
        }

        // Validate dữ liệu
        try {
            $validatedData = $request->validate([
                'diems' => 'required|array',
                'diems.*.Diem' => 'nullable|numeric|min:0|max:100',
                'diems.*.DiemThucHanh' => 'nullable|numeric|min:0|max:100',
                'diems.*.DiemLyThuyet' => 'nullable|numeric|min:0|max:100',
                'diems.*.DiemBaiTap' => 'nullable|numeric|min:0|max:100',
                'diems.*.GhiChu' => 'nullable|string|max:255',
            ], [
                'diems.required' => 'Dữ liệu điểm không hợp lệ.',
                'diems.*.Diem.numeric' => 'Điểm tổng phải là số.',
                'diems.*.Diem.min' => 'Điểm tổng tối thiểu là 0.',
                'diems.*.Diem.max' => 'Điểm tổng tối đa là 100.',
                'diems.*.DiemThucHanh.numeric' => 'Điểm thực hành phải là số.',
                'diems.*.DiemThucHanh.min' => 'Điểm thực hành tối thiểu là 0.',
                'diems.*.DiemThucHanh.max' => 'Điểm thực hành tối đa là 100.',
                'diems.*.DiemLyThuyet.numeric' => 'Điểm lý thuyết phải là số.',
                'diems.*.DiemLyThuyet.min' => 'Điểm lý thuyết tối thiểu là 0.',
                'diems.*.DiemLyThuyet.max' => 'Điểm lý thuyết tối đa là 100.',
                'diems.*.DiemBaiTap.numeric' => 'Điểm bài tập phải là số.',
                'diems.*.DiemBaiTap.min' => 'Điểm bài tập tối thiểu là 0.',
                'diems.*.DiemBaiTap.max' => 'Điểm bài tập tối đa là 100.',
                'diems.*.GhiChu.string' => 'Ghi chú phải là chuỗi văn bản.',
                'diems.*.GhiChu.max' => 'Ghi chú không được vượt quá 255 ký tự.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', [
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        }

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
                    'MaLop' => $lop->MaLop,
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

            return redirect()->route('giaovien.nhapdiemthi.nhap-diem', ['MaLop' => $lop->MaLop])
                ->with('success', 'Đã lưu điểm thành công');
        } catch (\Exception $e) {
            // Rollback transaction
            DB::rollBack();

            // Log the full exception
            \Log::error('Luu Diem Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi lưu điểm: ' . $e->getMessage());
        }
    }
}