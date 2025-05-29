<?php

namespace App\Http\Controllers\ToChucThi;

use App\Http\Controllers\Controller;
use App\Models\LichThi;
use App\Models\sinhvien;
use App\Models\LopHoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DanhSachDuThiController extends Controller
{
    // Lấy danh sách lớp để chọn
    public function getDanhSachLop()
    {
        $danhSachLop = LopHoc::all();
        return view('tochucthi.lichthi.chonlop', compact('danhSachLop'));
    }

    // Lấy danh sách lịch thi của lớp
    public function getLichThiTheoLop(Request $request)
    {
        $maLop = $request->input('ma_lop');
        $danhSachLichThi = LichThi::where('MaLop', $maLop)->get();
        return view('tochucthi.lichthi.chonlichthi', compact('danhSachLichThi', 'maLop'));
    }

    // Lấy danh sách sinh viên dự thi
    public function getDanhSachSinhVienDuThi(Request $request)
    {
        $maLop = $request->input('ma_lop');
        $maLichThi = $request->input('ma_lich_thi');

        // Truy vấn chi tiết sinh viên dự thi
        $danhSachSinhVien = DB::table('SinhVien')
            ->join('danhsachsv', 'SinhVien.MaSV', '=', 'danhsachsv.MaSV')
            ->join('lichthi', 'danhsachsv.MaLop', '=', 'lichthi.MaLop')
            ->where('lichthi.MaLichThi', $maLichThi)
            ->select(
                'SinhVien.MaSV', 
                'SinhVien.HoTen', 
                'SinhVien.Email', 
                'lichthi.NgayThi', 
                'lichthi.KhungGio', 
                'lichthi.PhongThi'
            )
            ->get();

        // Lấy thông tin chi tiết lịch thi
        $thongTinLichThi = LichThi::findOrFail($maLichThi);

        return view('tochucthi.lichthi.danhsachduthi', [
            'danhSachSinhVien' => $danhSachSinhVien,
            'thongTinLichThi' => $thongTinLichThi
        ]);
    }

    // Xuất danh sách ra file Excel
    public function xuatDanhSachExcel(Request $request)
    {
        $maLichThi = $request->input('ma_lich_thi');
        
        // Logic xuất Excel sử dụng thư viện Laravel Excel
        return Excel::download(new DanhSachDuThiExport($maLichThi), 'danh_sach_du_thi.xlsx');
    }
}
