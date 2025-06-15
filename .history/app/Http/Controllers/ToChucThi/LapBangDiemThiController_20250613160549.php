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
    public function getMonHocTheoLop(Request $request)
    {
        $maLop = $request->input('maLop');

        // Lấy môn học theo lớp và chương trình đào tạo
        $dsMonHoc = DB::table('lophoc')
            ->join('chuongtrinh', 'lophoc.MaChuongTrinh', '=', 'chuongtrinh.MaChuongTrinh')
            ->join('chuongtrinh_monhoc', 'chuongtrinh.MaChuongTrinh', '=', 'chuongtrinh_monhoc.MaChuongTrinh')
            ->join('monhoc', 'chuongtrinh_monhoc.MaMH', '=', 'monhoc.MaMH')
            ->where('lophoc.MaLop', $maLop)
            ->select('monhoc.MaMH', 'monhoc.TenMH')
            ->distinct()
            ->get();

        return response()->json($dsMonHoc);
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
    public function chonChuongTrinh()
    {
        // Lấy danh sách chương trình đào tạo
        $dsChuongTrinh = DB::table('chuongtrinh')->get();

        return view('tochucthi.diemthi.choose_program', [
            'dsChuongTrinh' => $dsChuongTrinh
        ]);
    }

    public function bangDiemTong($maChuongTrinh)
    {
        // Lấy thông tin chương trình đào tạo
        $chuongTrinh = DB::table('chuongtrinh')->where('MaChuongTrinh', $maChuongTrinh)->first();

        // Lấy danh sách môn học trong chương trình
        $danhSachMonHoc = DB::table('chuongtrinh_monhoc')
            ->join('monhoc', 'chuongtrinh_monhoc.MaMH', '=', 'monhoc.MaMH')
            ->where('MaChuongTrinh', $maChuongTrinh)
            ->select('monhoc.MaMH', 'monhoc.TenMH')
            ->distinct()
            ->get();

        // Lấy danh sách sinh viên trong các lớp của chương trình
        $danhSachSinhVien = DB::table('lophoc')
            ->join('danhsachsv', 'lophoc.MaLop', '=', 'sinhvien.MaLop')
            ->where('lophoc.MaChuongTrinh', $maChuongTrinh)
            ->select('sinhvien.MaSV', 'sinhvien.HoTen')
            ->distinct()
            ->get();

        // Lấy tiêu chí xếp loại
        $tieuChiXepLoai = DB::table('tieu_chi_xep_loai')
            ->where('MaChuongTrinh', $maChuongTrinh)
            ->orderBy('DiemTu')
            ->get();

        // Tính điểm tổng cho từng sinh viên
        $bangDiemTong = $danhSachSinhVien->map(function ($sinhVien) use ($danhSachMonHoc, $tieuChiXepLoai) {
            $diemCacMon = DB::table('diemthi')
                ->where('MaSV', $sinhVien->MaSV)
                ->whereIn('MaMH', $danhSachMonHoc->pluck('MaMH'))
                ->get();

            $tongDiem = 0;
            $diemChiTiet = [];

            foreach ($danhSachMonHoc as $monHoc) {
                $diemMon = $diemCacMon->firstWhere('MaMH', $monHoc->MaMH);
                $diemChiTiet[$monHoc->MaMH] = $diemMon ? $diemMon->DiemTong : 0;
                $tongDiem += $diemChiTiet[$monHoc->MaMH];
            }

            $diemTrungBinh = count($danhSachMonHoc) > 0 ? $tongDiem / count($danhSachMonHoc) : 0;

            // Xếp loại
            $xepLoai = $this->xepLoaiHocLuc($diemTrungBinh, $tieuChiXepLoai);

            return [
                'MaSV' => $sinhVien->MaSV,
                'HoTen' => $sinhVien->HoTen,
                'DiemChiTiet' => $diemChiTiet,
                'DiemTrungBinh' => $diemTrungBinh,
                'XepLoai' => $xepLoai
            ];
        });

        return view('tochucthi.diemthi.scoreboard_all', [
            'chuongTrinh' => $chuongTrinh,
            'danhSachMonHoc' => $danhSachMonHoc,
            'bangDiemTong' => $bangDiemTong
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
    // Xuất Excel bảng điểm chi tiết
    public function xuatBangDiemChiTiet($maLop, $maMH)
    {
        // Lấy thông tin lớp học
        $lopHoc = DB::table('lophoc')->where('MaLop', $maLop)->first();

        // Lấy thông tin môn học
        $monHoc = DB::table('monhoc')->where('MaMH', $maMH)->first();
        $tenMH = $monHoc->TenMH;
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

        return \Excel::download(
            new \App\Exports\BangDiemChiTietExport($danhSachDiem, $lopHoc, $monHoc, $chuongTrinh),
            "bang_diem_chi_tiet_{$maLop}_{$tenMH}.xlsx"
        );
    }

}
