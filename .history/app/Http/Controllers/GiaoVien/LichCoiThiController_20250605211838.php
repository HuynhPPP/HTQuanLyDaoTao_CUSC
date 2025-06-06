<?php

namespace App\Http\Controllers\GiaoVien;

use App\Http\Controllers\Controller;
use App\Models\GiaoVien;
use App\Models\sinhvien;
use App\Models\LichThi;
use App\Models\PhieuPhanCongThi;
use Illuminate\Support\Facades\Auth;

class LichCoiThiController extends Controller
{
    public function index()
    {
        // Lấy thông tin giảng viên hiện tại
        $id = session('id');
        $giaoVien = GiaoVien::where('MaGV', $id)->first();

        if (!$giaoVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên');
        }

        // Lấy danh sách lịch coi thi của giảng viên
        $lichCoiThi = PhieuPhanCongThi::with(['lichThi', 'lichThi.monHoc'])
            ->where('MaCB', $giaoVien->MaGV)
            ->get();

        return view('giaovien.lichthi.index', compact('lichCoiThi'));
    }

    public function chiTietLichThi($maLichThi)
    {
        // Lấy thông tin giảng viên hiện tại
        $id = session('id');
        $giaoVien = GiaoVien::where('MaGV', $id)->first();

        if (!$giaoVien) {
            return redirect()->back()->with('error', 'Không tìm thấy thông tin giảng viên');
        }

        // Kiểm tra xem giảng viên có được phân công coi thi không
        $phanCongThi = PhieuPhanCongThi::where('MaCB', $giaoVien->MaGV)
            ->where('MaLichThi', $maLichThi)
            ->first();

        if (!$phanCongThi) {
            return redirect()->back()->with('error', 'Bạn không được phân công coi thi cho lịch thi này');
        }

        // Lấy chi tiết lịch thi
        $lichThi = LichThi::with(['monHoc', 'phongThi'])->findOrFail($maLichThi);

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
                'sinhvien_duthi.GhiChu'
            )
            ->get();

        return view('giaovien.lichthi.chi-tiet', compact('lichThi', 'phanCongThi', 'danhSachSinhVien'));
    }
}