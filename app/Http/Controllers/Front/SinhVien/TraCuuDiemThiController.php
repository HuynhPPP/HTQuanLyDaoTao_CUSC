<?php

namespace App\Http\Controllers\Front\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LdapAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TraCuuDiemThiController extends Controller
{
    // Hiển thị bảng điểm trực tiếp cho sinh viên đã đăng nhập
    public function index()
    {
        $username = session('user');
        if (!$username) {
            return redirect()->route('login')->with('error', 'Bạn chưa đăng nhập!');
        }

        $ldap = LdapAccount::where('username', $username)->first();
        if (!$ldap) {
            return redirect()->back()->with('error', 'Không tìm thấy tài khoản');
        }

        $maSV = $ldap->MaTaiKhoan;

        // Lấy thông tin sinh viên
        $sinhVien = DB::table('sinhvien')->where('MaSV', $maSV)->first();

        // Lấy lớp của sinh viên (từ bảng danh sách SV)
        $lopHoc = DB::table('danhsachsv')
            ->where('MaSV', $maSV)
            ->first();

        if (!$lopHoc) {
            return back()->with('error', 'Sinh viên chưa được phân lớp');
        }

        // Lấy thông tin lớp (tên lớp, mã chương trình)
        $lopInfo = DB::table('lophoc')
            ->where('MaLop', $lopHoc->MaLop)
            ->first();

        // Lấy danh sách điểm thi của sinh viên theo CTĐT
        $danhSachDiem = DB::table('chuongtrinh_monhoc')
            ->join('monhoc', 'chuongtrinh_monhoc.MaMH', '=', 'monhoc.MaMH')
            ->leftJoin('diemthi', function ($join) use ($maSV) {
                $join->on('monhoc.MaMH', '=', 'diemthi.MaMH')
                    ->where('diemthi.MaSV', '=', $maSV);
            })
            ->where('chuongtrinh_monhoc.MaChuongTrinh', $lopInfo->MaChuongTrinh)
            ->select(
                'monhoc.MaMH',
                'monhoc.TenMH',
                'diemthi.MaLop',
                'diemthi.DiemLyThuyet',
                'diemthi.DiemThucHanh',
                'diemthi.DiemDuAn',
                'diemthi.DiemTong',
                'diemthi.GhiChu'
            )
            ->get()
            ->map(function ($item) use ($lopHoc) {
                $item->MaLop = $item->MaLop ?? $lopHoc->MaLop;
                return $item;
            });

        return view('frontend.sinhvien.tra_cuu_diem_thi.ketqua', [
            'sinhVien' => $sinhVien,
            'danhSachDiem' => $danhSachDiem,
            'MaLop' => $lopHoc->MaLop,
            'TenLop' => optional($lopInfo)->TenLop,
        ]);
    }

    // Tra cứu điểm theo mã sinh viên
    public function traCuuDiem(Request $request)
    {
        $request->validate([
            'MaSV' => 'required|exists:sinhvien,MaSV'
        ], [
            'MaSV.required' => 'Vui lòng nhập mã sinh viên.',
            'MaSV.exists' => 'Mã sinh viên không tồn tại.'
        ]);

        $maSV = $request->input('MaSV');

        // Lấy thông tin sinh viên
        $sinhVien = DB::table('sinhvien')->where('MaSV', $maSV)->first();

        // Lấy lớp của sinh viên
        $lopHoc = DB::table('danhsachsv')
            ->where('MaSV', $maSV)
            ->first();

        if (!$lopHoc) {
            return back()->with('error', 'Sinh viên chưa được phân lớp');
        }

        // Lấy danh sách môn học theo chương trình đào tạo của lớp
        $chuongTrinh = DB::table('lophoc')
            ->where('MaLop', $lopHoc->MaLop)
            ->first();

        $tatCaMonHoc = DB::table('chuongtrinh_monhoc')
            ->join('monhoc', 'chuongtrinh_monhoc.MaMH', '=', 'monhoc.MaMH')
            ->where('chuongtrinh_monhoc.MaChuongTrinh', $chuongTrinh->MaChuongTrinh)
            ->select('monhoc.MaMH', 'monhoc.TenMH')
            ->get();

        // Lấy danh sách điểm thi của sinh viên
        $danhSachDiem = DB::table('diemthi')
            ->rightJoin('monhoc', 'diemthi.MaMH', '=', 'monhoc.MaMH')
            ->join('chuongtrinh_monhoc', 'monhoc.MaMH', '=', 'chuongtrinh_monhoc.MaMH')
            ->where('chuongtrinh_monhoc.MaChuongTrinh', $chuongTrinh->MaChuongTrinh)
            ->where(function($query) use ($maSV, $lopHoc) {
                $query->where('diemthi.MaSV', $maSV)
                      ->orWhereNull('diemthi.MaSV');
            })
            ->select(
                'monhoc.MaMH', 
                'monhoc.TenMH', 
                'diemthi.MaLop', 
                'diemthi.DiemLyThuyet', 
                'diemthi.DiemThucHanh', 
                'diemthi.DiemDuAn', 
                'diemthi.DiemTong',
                'diemthi.GhiChu'
            )
            ->get()
            ->map(function ($item) use ($lopHoc) {
                // Nếu không có điểm, gán lớp của sinh viên
                $item->MaLop = $item->MaLop ?? $lopHoc->MaLop;
                return $item;
            });

        return view('frontend.sinhvien.tra_cuu_diem_thi.ketqua', [
            'sinhVien' => $sinhVien,
            'danhSachDiem' => $danhSachDiem,
            'lopHoc' => $lopHoc->MaLop
        ]);
    }
} 