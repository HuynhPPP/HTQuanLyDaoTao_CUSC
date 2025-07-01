<?php

namespace App\Http\Controllers\ThongKe;

use App\Http\Controllers\Controller;

use App\Services\ThongKeService;
use Illuminate\Http\Request;

class ThongKeDashboardController extends Controller
{
    protected $thongKeService;

    public function __construct(ThongKeService $thongKeService)
    {
        $this->thongKeService = $thongKeService;
    }

    public function index()
    {
        // Thống kê sinh viên
        $thongKeSinhVien = $this->thongKeService->thongKeSinhVien();
        $tongSinhVien = $thongKeSinhVien->sum('tong_so_luong');
        $tongNam = $thongKeSinhVien->sum('nam');
        $tongNu = $thongKeSinhVien->sum('nu');

        // Thống kê giáo viên
        $thongKeGiaoVien = $this->thongKeService->thongKeGiangVien();
        $tongGiaoVien = $thongKeGiaoVien->sum('tong_so_luong');
        $tongNamGV = $thongKeGiaoVien->sum('nam');
        $tongNuGV = $thongKeGiaoVien->sum('nu');

        // Thống kê sinh viên theo chương trình đào tạo
        $sinhVienTheoChuongTrinh = $this->thongKeService->thongKeSinhVienTheoChuongTrinh();

        // Thống kê sinh viên theo lớp học
        $sinhVienTheoLop = $this->thongKeService->thongKeSinhVienTheoLop();

        // Thống kê môn học theo chương trình
        $monHocTheoChuongTrinh = $this->thongKeService->thongKeMonHocTheoChuongTrinh();
        $SoMonHoc = $this->thongKeService->thongKeMonHoc();

        $SoChuongTrinhDaoTao = $this->thongKeService->thongKeChuongTrinhDaoTao();

        // Thống kê tình trạng sinh viên
        $tinhTrangSinhVien = $this->thongKeService->thongKeTinhTrangSinhVien();

        return view('thong-ke.dashboard', [
            'title' => 'Bảng Điều Khiển Thống Kê',
            'tongSinhVien' => $tongSinhVien,
            'tongNam' => $tongNam,
            'tongNu' => $tongNu,
            'tongGiaoVien' => $tongGiaoVien,
            'tongNamGV' => $tongNamGV,
            'tongNuGV' => $tongNuGV,
            'sinhVienTheoChuongTrinh' => $sinhVienTheoChuongTrinh,
            'sinhVienTheoLop' => $sinhVienTheoLop,
            'monHocTheoChuongTrinh' => $monHocTheoChuongTrinh,
            'SoMonHoc' => $SoMonHoc,
            'tinhTrangSinhVien' => $tinhTrangSinhVien,
            'SoChuongTrinhDaoTao' => $SoChuongTrinhDaoTao
        ]);
    }

    public function tongKetHocLuc($MaLop, $MaChuongTrinh)
    {
        $lop = LopHoc::where('MaLop', $MaLop)->firstOrFail();

        $monHocIds = ChuongTrinhMonHoc::where('MaChuongTrinh', $MaChuongTrinh)
            ->pluck('MaMH')
            ->toArray();

        $tieuChi = TieuChiXepLoai::where('MaChuongTrinh', $MaChuongTrinh)
            ->orderBy('DiemTu')
            ->get();

        $danhSachSV = DiemThi::where('MaLop', $MaLop)
            ->whereIn('MaMH', $monHocIds)
            ->get()
            ->groupBy('MaSV');

        $ketQua = collect();

        foreach ($danhSachSV as $maSV => $diemCácMon) {
            $tong = $diemCácMon->avg('DiemTong');
            $xepLoai = 'Chưa xếp loại';
            foreach ($tieuChi as $tc) {
                if ($tong >= $tc->DiemTu && $tong < $tc->DiemDen) {
                    $xepLoai = $tc->XepLoai;
                    break;
                }
            }
            $sv = $diemCácMon->first()->sinhVien;
            $ketQua->push((object) [
                'MaSV' => $maSV,
                'HoTen' => $sv->HoTen,
                'DiemTB' => round($tong, 2),
                'XepLoai' => $xepLoai,
            ]);
        }

        return view('thong-ke.hoc-luc', [
            'lop' => $lop,
            'ketQua' => $ketQua,
        ]);
    }
}
