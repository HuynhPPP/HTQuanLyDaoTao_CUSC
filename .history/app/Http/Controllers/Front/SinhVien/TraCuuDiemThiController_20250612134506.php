<?php

namespace App\Http\Controllers\Front\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LdapAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TraCuuDiemThiController extends Controller
{
    // Hiển thị form tra cứu điểm
    public function index()
    {
        // Lấy thông tin tài khoản LDAP
        $id = session('id');
        $maSV = LdapAccount::where('MaTaiKhoan', $id)->first();
        
        return view('tracuudiem.index', [
            'maSV' => $maSV
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

        return view('tracuudiem.ketqua', [
            'sinhVien' => $sinhVien,
            'danhSachDiem' => $danhSachDiem,
            'lopHoc' => $lopHoc->MaLop
        ]);
    }
} 