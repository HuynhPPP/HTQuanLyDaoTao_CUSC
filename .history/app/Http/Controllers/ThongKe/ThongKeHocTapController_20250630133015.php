<?php

namespace App\Http\Controllers\ThongKe;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChuongTrinh;
use App\Models\SinhVien;
use App\Models\DiemThi;
use App\Models\ThongKeHocTap;
use App\Models\TieuChiXepLoai;
use Illuminate\Support\Facades\DB;
use App\Exports\ThongKeHocTapExport;
use Maatwebsite\Excel\Facades\Excel;

class ThongKeHocTapController extends Controller
{
    // Phương thức chọn chương trình đào tạo
    public function chonChuongTrinh()
    {
        $dsChuongTrinh = DB::table('chuongtrinh')
            ->select('MaChuongTrinh', 'TenChuongTrinh')
            ->get();

        return view('thong-ke.thongkehoctap.choose_program', [
            'dsChuongTrinh' => $dsChuongTrinh
        ]);
    }

    // Thống kê tổng quan theo chương trình đào tạo
    public function thongKeTongQuan($maChuongTrinh, $hocKi = null)
    {
        // Nếu không có học kỳ, lấy học kỳ hiện tại
        if (!$hocKi) {
            $hocKi = DB::table('hocki')
                ->value('MaHK');
        }

        // Lấy danh sách sinh viên trong chương trình
        $sinhViens = DB::table('sinhvien')
            ->where('MaChuongTrinh', $maChuongTrinh)
            ->get();

        // Thống kê điểm
        $thongKeDiem = $this->tinhToanThongKeDiem($maChuongTrinh, $hocKi);

        // Thống kê học lực
        $thongKeHocLuc = $this->tinhToanHocLuc($maChuongTrinh, $hocKi);

        return [
            'thongKeDiem' => $thongKeDiem,
            'thongKeHocLuc' => $thongKeHocLuc
        ];
    }

    // Tính toán thống kê điểm
    protected function tinhToanThongKeDiem($maChuongTrinh, $hocKi)
    {
        return DB::table('diemthi')
            ->join('sinhvien', 'diemthi.MaSV', '=', 'sinhvien.MaSV')
            ->where('sinhvien.MaChuongTrinh', $maChuongTrinh)
            ->where('diemthi.MaHocKi', $hocKi)
            ->select(
                DB::raw('COUNT(DISTINCT diemthi.MaSV) as tong_sinh_vien'),
                DB::raw('AVG(diemthi.DiemTong) as diem_trung_binh_tong_khoa'),
                DB::raw('SUM(CASE WHEN diemthi.DiemTong >= 8.0 THEN 1 ELSE 0 END) as sinh_vien_gioi'),
                DB::raw('SUM(CASE WHEN diemthi.DiemTong >= 6.5 AND diemthi.DiemTong < 8.0 THEN 1 ELSE 0 END) as sinh_vien_kha'),
                DB::raw('SUM(CASE WHEN diemthi.DiemTong >= 5.0 AND diemthi.DiemTong < 6.5 THEN 1 ELSE 0 END) as sinh_vien_trung_binh'),
                DB::raw('SUM(CASE WHEN diemthi.DiemTong < 5.0 THEN 1 ELSE 0 END) as sinh_vien_yeu')
            )
            ->first();
    }

    // Tính toán học lực
    protected function tinhToanHocLuc($maChuongTrinh, $hocKi)
    {
        // Lấy các tiêu chí xếp loại từ bảng tieu_chi_xep_loai
        $tieuChiXepLoai = DB::table('tieu_chi_xep_loai')->get();

        // Truy vấn điểm trung bình của sinh viên
        $diemSinhVien = DB::table('diemthi')
            ->join('sinhvien', 'diemthi.MaSV', '=', 'sinhvien.MaSV')
            ->where('sinhvien.MaChuongTrinh', $maChuongTrinh)
            ->where('diemthi.MaHocKi', $hocKi)
            ->select(
                'sinhvien.MaSV',
                'sinhvien.HoTen',
                DB::raw('AVG(diemthi.DiemTong) as diem_trung_binh')
            )
            ->groupBy('sinhvien.MaSV', 'sinhvien.HoTen')
            ->get();

        // Phân loại học lực
        $phanLoaiHocLuc = $diemSinhVien->map(function ($sv) use ($tieuChiXepLoai) {
            $hocLuc = 'Yếu';
            foreach ($tieuChiXepLoai as $tieu_chi) {
                if ($sv->diem_trung_binh >= $tieu_chi->DiemToiThieu) {
                    $hocLuc = $tieu_chi->TenXepLoai;
                    break;
                }
            }
            return [
                'MaSV' => $sv->MaSV,
                'HoTen' => $sv->HoTen,
                'DiemTrungBinh' => $sv->diem_trung_binh,
                'HocLuc' => $hocLuc
            ];
        });

        return $phanLoaiHocLuc;
    }

    // Báo cáo chi tiết
    public function baoCaoChiTiet($maChuongTrinh)
    {
        // Lấy thông tin chương trình
        $chuongTrinh = DB::table('chuongtrinh')
            ->where('MaChuongTrinh', $maChuongTrinh)
            ->first();

        // Lấy học kỳ hiện tại
        $hocKiHienTai = DB::table('hocki')
            ->first();

        // Thống kê tổng quan
        $thongKe = $this->thongKeTongQuan($maChuongTrinh, $hocKiHienTai->MaHocKi);

        // Biểu đồ phân bổ học lực
        $bieuDoPhanBoHocLuc = $this->taoBieuDoPhanBoHocLuc($thongKe['thongKeDiem']);

        // Xu hướng điểm số qua các học kỳ
        $xuHuongDiemSo = $this->layXuHuongDiemSo($maChuongTrinh);

        return view('thong-ke.bao-cao-chi-tiet', [
            'thongKe' => $thongKe['thongKeDiem'],
            'chuongTrinh' => $chuongTrinh,
            'bieuDoPhanBoHocLuc' => $bieuDoPhanBoHocLuc,
            'xuHuongDiemSo' => $xuHuongDiemSo,
            'danhSachHocLuc' => $thongKe['thongKeHocLuc']
        ]);
    }

    // Tạo biểu đồ phân bổ học lực
    protected function taoBieuDoPhanBoHocLuc($thongKe)
    {
        return [
            'labels' => ['Giỏi', 'Khá', 'Trung Bình', 'Yếu'],
            'data' => [
                $thongKe->sinh_vien_gioi ?? 0,
                $thongKe->sinh_vien_kha ?? 0,
                $thongKe->sinh_vien_trung_binh ?? 0,
                $thongKe->sinh_vien_yeu ?? 0
            ]
        ];
    }

    // Lấy xu hướng điểm số qua các học kỳ
    protected function layXuHuongDiemSo($maChuongTrinh)
    {
        return DB::table('diemthi')
            ->join('sinhvien', 'diemthi.MaSV', '=', 'sinhvien.MaSV')
            ->join('hocki', 'diemthi.MaHocKi', '=', 'hocki.MaHocKi')
            ->where('sinhvien.MaChuongTrinh', $maChuongTrinh)
            ->select(
                'hocki.MaHocKi',
                'hocki.TenHocKi',
                DB::raw('AVG(diemthi.DiemTong) as diem_trung_binh')
            )
            ->groupBy('hocki.MaHocKi', 'hocki.TenHocKi')
            ->orderBy('hocki.MaHocKi')
            ->get();
    }

    // Xuất báo cáo Excel
    public function xuatBaoCao($maChuongTrinh)
    {
        $thongKe = ThongKeHocTap::where('ma_chuong_trinh', $maChuongTrinh)
            ->orderBy('created_at', 'desc')
            ->first();

        return Excel::download(
            new ThongKeHocTapExport($thongKe),
            "thong_ke_hoc_tap_{$maChuongTrinh}.xlsx"
        );
    }
}
