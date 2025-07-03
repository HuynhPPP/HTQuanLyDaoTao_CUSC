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

    public function chonChuongTrinh()
    {
        // Lấy danh sách chương trình đào tạo
        $dsChuongTrinh = DB::table('chuongtrinh')->get();

        return view('thong-ke.thongkehoctap.choose_program', [
            'dsChuongTrinh' => $dsChuongTrinh
        ]);
    }
    // Phương thức thống kê tổng quan
    public function thongKeTongQuan($maChuongTrinh, $hocKi)
    {
        // Lấy danh sách sinh viên trong chương trình
        $sinhViens = SinhVien::where('MaChuongTrinh', $maChuongTrinh)->get();

        // Tính toán các chỉ số thống kê
        $thongKe = $this->tinhToanThongKe($sinhViens, $hocKi);

        // Lưu thống kê vào cơ sở dữ liệu
        return $this->luuThongKe($maChuongTrinh, $hocKi, $thongKe);
    }

    // Tính toán các chỉ số thống kê
    protected function tinhToanThongKe($sinhViens, $hocKi)
    {
        $thongKe = [
            'tong_sinh_vien' => $sinhViens->count(),
            'sinh_vien_gioi' => 0,
            'sinh_vien_kha' => 0,
            'sinh_vien_trung_binh' => 0,
            'sinh_vien_yeu' => 0,
            'diem_trung_binh_tong_khoa' => 0,
            'ty_le_tot_nghiep' => 0
        ];

        $tongDiem = 0;
        $sinhVienDat = 0;

        foreach ($sinhViens as $sinhVien) {
            // Lấy điểm trung bình của sinh viên
            $diemTrungBinh = DiemThi::where('MaSV', $sinhVien->MaSV)
                ->where('HocKi', $hocKi)
                ->avg('DiemTong');

            // Xếp loại học lực
            $hocLuc = $this->xepLoaiHocLuc($diemTrungBinh);

            // Đếm số lượng sinh viên theo học lực
            switch ($hocLuc) {
                case 'Xuất sắc':
                case 'Giỏi':
                    $thongKe['sinh_vien_gioi']++;
                    break;
                case 'Khá':
                    $thongKe['sinh_vien_kha']++;
                    break;
                case 'Trung bình':
                    $thongKe['sinh_vien_trung_binh']++;
                    break;
                default:
                    $thongKe['sinh_vien_yeu']++;
            }

            // Tính điểm trung bình tổng khoa
            $tongDiem += $diemTrungBinh;

            // Kiểm tra sinh viên đạt yêu cầu tốt nghiệp
            if ($diemTrungBinh >= 50) {
                $sinhVienDat++;
            }
        }

        // Tính điểm trung bình tổng khoa
        $thongKe['diem_trung_binh_tong_khoa'] = $sinhViens->count() > 0
            ? round($tongDiem / $sinhViens->count(), 2)
            : 0;

        // Tính tỷ lệ tốt nghiệp
        $thongKe['ty_le_tot_nghiep'] = $sinhViens->count() > 0
            ? round(($sinhVienDat / $sinhViens->count()) * 100, 2)
            : 0;

        return $thongKe;
    }

    // Lưu thống kê vào cơ sở dữ liệu
    protected function luuThongKe($maChuongTrinh, $hocKi, $thongKe)
    {
        return ThongKeHocTap::updateOrCreate(
            [
                'ma_chuong_trinh' => $maChuongTrinh,
                'hoc_ki' => $hocKi
            ],
            $thongKe
        );
    }

    // Xếp loại học lực
    protected function xepLoaiHocLuc($diemTrungBinh)
    {
        if ($diemTrungBinh >= 9.0)
            return 'Xuất sắc';
        if ($diemTrungBinh >= 8.0)
            return 'Giỏi';
        if ($diemTrungBinh >= 6.5)
            return 'Khá';
        if ($diemTrungBinh >= 5.0)
            return 'Trung bình';
        return 'Yếu';
    }

    // Báo cáo chi tiết
    public function baoCaoChiTiet($maChuongTrinh)
    {
        // Lấy thống kê mới nhất
        $thongKe = ThongKeHocTap::where('ma_chuong_trinh', $maChuongTrinh)
            ->orderBy('created_at', 'desc')
            ->first();

        // Nếu không có thống kê, tạo một thống kê mặc định
        if (!$thongKe) {
            $thongKe = new ThongKeHocTap([
                'ma_chuong_trinh' => $maChuongTrinh,
                'tong_sinh_vien' => 0,
                'sinh_vien_gioi' => 0,
                'sinh_vien_kha' => 0,
                'sinh_vien_trung_binh' => 0,
                'sinh_vien_yeu' => 0,
                'diem_trung_binh_tong_khoa' => 0,
                'ty_le_tot_nghiep' => 0
            ]);
        }

        // Lấy thông tin chương trình đào tạo
        $chuongTrinh = ChuongTrinh::findOrFail($maChuongTrinh);

        // Biểu đồ phân bổ học lực
        $bieuDoPhanBoHocLuc = $this->taoBieuDoPhanBoHocLuc($thongKe);

        // Xu hướng điểm số qua các học kỳ
        $xuHuongDiemSo = $this->layXuHuongDiemSo($maChuongTrinh);

        return view('thong-ke.bao-cao-chi-tiet', [
            'thongKe' => $thongKe,
            'chuongTrinh' => $chuongTrinh,
            'bieuDoPhanBoHocLuc' => $bieuDoPhanBoHocLuc,
            'xuHuongDiemSo' => $xuHuongDiemSo
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
        return ThongKeHocTap::where('ma_chuong_trinh', $maChuongTrinh)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($thongKe) {
                return [
                    'hoc_ki' => $thongKe->hoc_ki,
                    'diem_trung_binh' => $thongKe->diem_trung_binh_tong_khoa
                ];
            });
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
