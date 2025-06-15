<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TraCuuDiemThiController extends Controller
{
    // Hiển thị form tra cứu điểm
    public function index()
    {
        // Nếu là sinh viên đã đăng nhập, tự động điền mã sinh viên
        $maSV = Auth::guard('sinhvien')->check() ? Auth::guard('sinhvien')->user()->MaSV : null;
        
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

        // Lấy danh sách điểm thi của sinh viên
        $danhSachDiem = DB::table('diemthi')
            ->join('monhoc', 'diemthi.MaMH', '=', 'monhoc.MaMH')
            ->join('lophoc', 'diemthi.MaLop', '=', 'lophoc.MaLop')
            ->where('diemthi.MaSV', $maSV)
            ->select(
                'monhoc.TenMH', 
                'lophoc.MaLop', 
                'diemthi.DiemLyThuyet', 
                'diemthi.DiemThucHanh', 
                'diemthi.DiemDuAn', 
                'diemthi.DiemTong',
                'diemthi.GhiChu'
            )
            ->get();

        // Lấy thông tin sinh viên
        $sinhVien = DB::table('sinhvien')->where('MaSV', $maSV)->first();

        return view('tracuudiem.ketqua', [
            'sinhVien' => $sinhVien,
            'danhSachDiem' => $danhSachDiem
        ]);
    }
} 