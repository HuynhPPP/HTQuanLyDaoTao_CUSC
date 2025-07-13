<?php

namespace App\Http\Controllers\ThongKe;

use App\Http\Controllers\Controller;

use App\Models\ChuongTrinh;
use App\Services\ThongKeService;
use App\Models\danhsachsv;
use App\Models\ChuongTrinhMonHoc;
use App\Models\TieuChiXepLoai;
use App\Models\DiemThi;
use App\Models\LopHoc;
use Illuminate\Support\Facades\DB;
use App\Exports\ThongKeKetQuaHocTapExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\MonHoc;
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
    public function chonChuongTrinhLopHoc()
    {
        $dsChuongTrinh = DB::table('chuongtrinh')->get();
        $dsLop = DB::table('lophoc')->get(); // lấy thêm danh sách lớp

        return view('thong-ke.choose_program_class', [
            'dsChuongTrinh' => $dsChuongTrinh,
            'dsLop' => $dsLop,
        ]);
    }
    public function tongKetHocLuc($MaLop, $MaChuongTrinh)
    {
        $lop = LopHoc::where('MaLop', $MaLop)->firstOrFail();
        $MaChuongTrinh = ChuongTrinh::where('MaChuongTrinh', $MaChuongTrinh)->firstOrFail();

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

        foreach ($danhSachSV as $maSV => $diemCacMon) {
            $tong = $diemCacMon->avg('DiemTong');
            $xepLoai = 'Chưa xếp loại';
            foreach ($tieuChi as $tc) {
                if ($tong >= $tc->DiemTu && $tong < $tc->DiemDen) {
                    $xepLoai = $tc->XepLoai;
                    break;
                }
            }
            $sv = $diemCacMon->first()->sinhVien;
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
            'MaChuongTrinh' => $MaChuongTrinh
        ]);
    }
    public function thongkehoctap($MaLop, $MaChuongTrinh)
    {
        $lop = LopHoc::where('MaLop', $MaLop)->first();
        $MaCT = ChuongTrinh::where('MaChuongTrinh', $MaChuongTrinh)->firstOrFail();
        $monHocs = ChuongTrinhMonHoc::where('MaChuongTrinh', $MaChuongTrinh)->pluck('MaMH');
        $dsDiem = DiemThi::where('MaLop', $MaLop)->whereIn('MaMH', $monHocs)->get();

        // Dữ liệu mỗi tab
        $theoMon = $dsDiem->groupBy('MaMH');
        $thongKeDat = $dsDiem->groupBy('MaMH')->map(function ($ds) {
            return [
                'dat' => $ds->where('DiemTong', '>=', 40)->count(),
                'khongDat' => $ds->where('DiemTong', '<', 40)->count(),
                'tong' => $ds->count()
            ];
        });

        // Tổng kết học lực như đã viết ở trước
        $tieuChi = TieuChiXepLoai::where('MaChuongTrinh', $MaChuongTrinh)->get();
        $tongKet = $dsDiem->groupBy('MaSV')->map(function ($ds) use ($tieuChi) {
            $tb = $ds->avg('DiemTong');
            $loai = 'Chưa xếp loại';
            foreach ($tieuChi as $tc) {
                if ($tb >= $tc->DiemTu && $tb < $tc->DiemDen)
                    $loai = $tc->XepLoai;
            }
            return [
                'MaSV' => $ds->first()->MaSV,
                'HoTen' => $ds->first()->sinhVien->HoTen ?? '',
                'DiemTB' => round($tb, 2),
                'XepLoai' => $loai
            ];
        });

        return view('thong-ke.thongkehoctap_dashboard', compact('lop', 'theoMon', 'thongKeDat', 'tongKet', 'MaCT'));
    }
    public function exportExcel($MaLop, $MaChuongTrinh)
    {
        $lop = LopHoc::where('MaLop', $MaLop)->first();
        $monHocs = ChuongTrinhMonHoc::where('MaChuongTrinh', $MaChuongTrinh)->pluck('MaMH');
        $dsDiem = DiemThi::where('MaLop', $MaLop)->whereIn('MaMH', $monHocs)->get();

        $theoMon = $dsDiem->groupBy('MaMH');
        $thongKeDat = $dsDiem->groupBy('MaMH')->map(function ($ds) {
            return [
                'dat' => $ds->where('DiemTong', '>=', 40)->count(),
                'khongDat' => $ds->where('DiemTong', '<', 40)->count(),
                'tong' => $ds->count()
            ];
        });

        $tieuChi = TieuChiXepLoai::where('MaChuongTrinh', $MaChuongTrinh)->get();
        $tongKet = $dsDiem->groupBy('MaSV')->map(function ($ds) use ($tieuChi) {
            $tb = $ds->avg('DiemTong');
            $loai = 'Chưa xếp loại';
            foreach ($tieuChi as $tc) {
                if ($tb >= $tc->DiemTu && $tb < $tc->DiemDen)
                    $loai = $tc->XepLoai;
            }
            return [
                'MaSV' => $ds->first()->MaSV,
                'HoTen' => $ds->first()->sinhVien->HoTen ?? '',
                'DiemTB' => round($tb, 2),
                'XepLoai' => $loai
            ];
        });

        return Excel::download(
            new ThongKeKetQuaHocTapExport($lop, $theoMon, $thongKeDat, $tongKet),
            'ThongKeHocTap_' . $lop->MaLop . '.xlsx'
        );
    }
}
