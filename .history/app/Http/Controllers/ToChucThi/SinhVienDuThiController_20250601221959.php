<?php

namespace App\Http\Controllers\ToChucThi;

use App\Http\Controllers\Controller;
use App\Models\LichThi;
use App\Models\LopHoc;
use App\Models\sinhvien;
use App\Models\SinhVienDuThi;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DanhSachSinhVienDuThiExport;

class SinhVienDuThiController extends Controller
{

    // Hiển thị form đăng ký sinh viên dự thi
    public function danhSachSinhVienDuThi($maLichThi)
    {
        // Lấy thông tin lịch thi
        $lichThi = LichThi::with(['lopHoc', 'monHoc', 'phanCongThi'])->findOrFail($maLichThi);
        $canBo = CanBo::findOrFail($lichThi->phanCongThi->MaCB);
        // Lấy danh sách sinh viên trong lớp
        $danhSachSinhVien = sinhvien::join('danhsachsv', 'SinhVien.MaSV', '=', 'danhsachsv.MaSV')
            ->where('danhsachsv.MaLop', $lichThi->MaLop)
            ->leftJoin('sinhvien_duthi', function ($join) use ($maLichThi) {
                $join->on('SinhVien.MaSV', '=', 'sinhvien_duthi.MaSV')
                    ->where('sinhvien_duthi.MaLichThi', $maLichThi);
            })
            ->select(
                'SinhVien.MaSV',
                'SinhVien.HoTen',
                'SinhVien.Email',
                'SinhVien.Sdt',
                'sinhvien_duthi.TrangThaiDuThi',
                'sinhvien_duthi.GhiChu'
            )
            ->get();

        return view('tochucthi.lichthi.danh_sach_sinh_vien_du_thi', [
            'lichThi' => $lichThi,
            'danhSachSinhVien' => $danhSachSinhVien
        ]);
    }

    // Lưu danh sách sinh viên dự thi
    public function luuDanhSachDuThi(Request $request)
    {
        $maLichThi = $request->input('MaLichThi');
        $lichThi = LichThi::findOrFail($maLichThi);

        $danhSachSinhVien = $request->input('sinhvien', []);

        // Xóa danh sách cũ
        SinhVienDuThi::where('MaLichThi', $maLichThi)->delete();

        // Thêm danh sách mới
        foreach ($danhSachSinhVien as $maSV => $thongTin) {
            SinhVienDuThi::create([
                'MaSV' => $maSV,
                'MaLichThi' => $maLichThi,
                'MaLop' => $lichThi->MaLop,
                'TrangThaiDuThi' => $thongTin['TrangThaiDuThi'] ?? 'DangKy',
                'GhiChu' => $thongTin['GhiChu'] ?? null
            ]);
        }

        return redirect()
            ->route('sinhvien.duthi.danh-sach', $maLichThi)
            ->with('success', 'Đã lưu danh sách sinh viên dự thi thành công');
    }

    // Xuất Excel danh sách sinh viên dự thi
    public function xuatExcel($maLichThi)
    {
        $lichThi = LichThi::findOrFail($maLichThi);
        return Excel::download(new DanhSachSinhVienDuThiExport($maLichThi), "danh_sach_du_thi_{$lichThi->TenMH}_{$lichThi->MaLop}_{$lichThi->NgayThi}.xlsx");
    }
}
