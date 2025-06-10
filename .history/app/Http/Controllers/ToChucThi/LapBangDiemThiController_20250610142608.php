<?php

namespace App\Http\Controllers\ToChucThi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class LapBangDiemThiController extends Controller
{

    public function chonLopVaMonHoc()
    {
        // Lấy danh sách lớp học
        $dsLop = DB::table('lophoc')->get();

        return view('tochucthi.diemthi.choose_subject_class', [
            'dsLop' => $dsLop
        ]);
    }
    public function bangDiemChiTiet($maLop, $maMH)
    {
        // Lấy thông tin lớp học
        $lopHoc = DB::table('lophoc')->where('MaLop', $maLop)->first();

        // Lấy thông tin môn học
        $monHoc = DB::table('monhoc')->where('MaMH', $maMH)->first();

        // Lấy danh sách điểm thi của lớp và môn học
        $danhSachDiem = DB::table('diemthi')
            ->join('sinhvien', 'diemthi.MaSV', '=', 'sinhvien.MaSV')
            ->where('diemthi.MaLop', $maLop)
            ->where('diemthi.MaMH', $maMH)
            ->select('sinhvien.MaSV', 'sinhvien.HoTen', 'diemthi.*')
            ->get();

        // Lấy thông tin chương trình đào tạo
        $chuongTrinh = DB::table('chuongtrinh')->where('MaChuongTrinh', $lopHoc->MaChuongTrinh)->first();

        // Lấy tiêu chí xếp loại
        $tieuChiXepLoai = DB::table('tieu_chi_xep_loai')
            ->where('MaChuongTrinh', $chuongTrinh->MaChuongTrinh)
            ->orderBy('DiemTu')
            ->get();

        // Xử lý xếp loại cho từng sinh viên
        $danhSachDiem = $danhSachDiem->map(function ($item) use ($tieuChiXepLoai) {
            $item->XepLoai = $this->xepLoaiHocLuc($item->DiemTong, $tieuChiXepLoai);
            return $item;
        });

        return view('tochucthi.diemthi.scoreboard_detaile', [
            'lopHoc' => $lopHoc,
            'monHoc' => $monHoc,
            'chuongTrinh' => $chuongTrinh,
            'danhSachDiem' => $danhSachDiem
        ]);
    }

    private function xepLoaiHocLuc($diemTong, $tieuChiXepLoai)
    {
        foreach ($tieuChiXepLoai as $tieuChi) {
            if ($diemTong >= $tieuChi->DiemTu && $diemTong < $tieuChi->DiemDen) {
                return $tieuChi->XepLoai;
            }
        }
        return 'Chưa xếp loại';
    }
}
