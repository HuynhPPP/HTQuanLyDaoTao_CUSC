<?php

namespace App\Http\Controllers;


use App\Models\SinhVien;
use App\Models\MonHoc;
use App\Models\LopHoc;
use App\Models\ChuongTrinh;
use App\Models\DiemThi;
use App\Models\ChuongTrinhMonHoc;
use App\Models\TieuChiXepLoai;
use App\Models\tkb;
use App\Models\danhsachmonhoc;
use App\Models\LichThi;
use App\Models\HinhThucDanhGia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TienTrinhHocTapExport;

class TienTrinhHocTapController extends Controller
{
    /**
     * Hiển thị tiến trình học tập của sinh viên
     */
    public function show($maSV)
    {
        $sinhVien = SinhVien::where('MaSV', $maSV)->firstOrFail();
        
        // Lấy chương trình đào tạo của sinh viên
        $maChuongTrinh = null;
        $diemThiMonHocIds = DiemThi::where('MaSV', $maSV)->pluck('MaMH')->unique();

        if ($diemThiMonHocIds->isNotEmpty()) {
            $chuongTrinhMonHoc = ChuongTrinhMonHoc::whereIn('MaMH', $diemThiMonHocIds)->first();
            if ($chuongTrinhMonHoc) {
                $maChuongTrinh = $chuongTrinhMonHoc->MaChuongTrinh;
            }
        }

        // Lấy tất cả môn học trong chương trình đào tạo
        $monHocTrongChuongTrinh = collect();
        if ($maChuongTrinh) {
            $monHocTrongChuongTrinh = ChuongTrinhMonHoc::where('MaChuongTrinh', $maChuongTrinh)
                ->with('monHoc')
                ->get();
        }

        // Lấy điểm thi của sinh viên
        $diemThis = DiemThi::with(['monHoc', 'lopHoc'])
            ->where('MaSV', $maSV)
            ->get();
        
        // Lấy cấu hình hình thức đánh giá theo CTĐT (nếu có)
        $hinhThucDanhGias = $maChuongTrinh
            ? HinhThucDanhGia::where('MaChuongTrinh', $maChuongTrinh)->orderBy('id')->get()
            : collect();

        // Tạo dữ liệu tiến trình từ tất cả môn học trong chương trình
        $tienTrinh = collect();
        
        // Thêm các môn học từ chương trình đào tạo
        foreach ($monHocTrongChuongTrinh as $monHocCT) {
            // Tìm điểm thi tương ứng nếu có
            $diemThi = $diemThis->where('MaMH', $monHocCT->MaMH)->first();
            
            // Tính tổng điểm theo hình thức đánh giá của CTĐT (nếu có)
            $tongDiemTinhLai = null;
            if ($diemThi) {
                if ($hinhThucDanhGias->count() > 0) {
                    $mapField = function (string $hinhThuc): ?string {
                        $lh = mb_strtolower($hinhThuc, 'UTF-8');
                        if (strpos($lh, 'lý thuyết') !== false || strpos($lh, 'ly thuyet') !== false) return 'DiemLyThuyet';
                        if (strpos($lh, 'thực hành') !== false || strpos($lh, 'thuc hanh') !== false) return 'DiemThucHanh';
                        if (strpos($lh, 'dự án') !== false || strpos($lh, 'du an') !== false) return 'DiemDuAn';
                        return null;
                    };
                    $sum = 0.0;
                    foreach ($hinhThucDanhGias as $ht) {
                        $field = $mapField($ht->HinhThuc ?? '');
                        if ($field && isset($diemThi->$field)) {
                            $tiLe = (float) ($ht->TiLePhanTram ?? 0) / 100.0;
                            $sum += (float) ($diemThi->$field ?? 0) * $tiLe;
                        }
                    }
                    $tongDiemTinhLai = round($sum, 2);
                } else {
                    $tongDiemTinhLai = $diemThi->DiemTong !== null ? round((float) $diemThi->DiemTong, 2) : null;
                }
            }

            // Xác định trạng thái học tập
            $trangThai = $this->xacDinhTrangThai(
                $tongDiemTinhLai,
                $maSV,
                $monHocCT->MaMH,
                $diemThi ? $diemThi->MaLop : null,
                $maChuongTrinh
            );

            $tienTrinh->push((object) [
                'MaSV' => $maSV,
                'MaMH' => $monHocCT->MaMH,
                'MaLop' => $diemThi ? $diemThi->MaLop : null,
                'DiemTong' => $diemThi ? $diemThi->DiemTong : null,
                'TongDiemTinhLai' => $tongDiemTinhLai,
                'DiemLyThuyet' => $diemThi ? $diemThi->DiemLyThuyet : null,
                'DiemThucHanh' => $diemThi ? $diemThi->DiemThucHanh : null,
                'DiemDuAn' => $diemThi ? $diemThi->DiemDuAn : null,
                'GhiChu' => $diemThi ? $diemThi->GhiChu : null,
                'TrangThai' => $trangThai,
                'XepLoai' => $this->xacDinhXepLoai($tongDiemTinhLai, $maChuongTrinh),
                'NgayHoanThanh' => $trangThai === 'DaHoanThanh' ? now() : null,
                'monHoc' => $monHocCT->monHoc,
                'lopHoc' => $diemThi ? $diemThi->lopHoc : null,
                'SoTinChi' => $monHocCT->monHoc->GioTrienKhai ?? 0,
                'MaChuongTrinh' => $maChuongTrinh
            ]);
        }

        // Thống kê tổng quan
        $thongKe = [
            'tongMonHoc' => $tienTrinh->count(),
            'monDaHoanThanh' => $tienTrinh->where('TrangThai', 'DaHoanThanh')->count(),
            'monDangHoc' => $tienTrinh->where('TrangThai', 'DangHoc')->count(),
            'monDaDangKy' => $tienTrinh->where('TrangThai', 'DangKy')->count(),
            'monChuaDangKy' => $tienTrinh->where('TrangThai', 'ChuaDangKy')->count(),
            'tongTinChi' => $tienTrinh->where('TrangThai', 'DaHoanThanh')->sum('SoTinChi'),
            'diemTrungBinh' => $tienTrinh->whereNotNull('TongDiemTinhLai')->avg('TongDiemTinhLai')
                ?? ($tienTrinh->whereNotNull('DiemTong')->avg('DiemTong') ?? 0),
            'xepLoai' => $this->xacDinhXepLoaiChung($tienTrinh)
        ];

        return view('tien_trinh_hoc_tap.show', compact('sinhVien', 'tienTrinh', 'thongKe'));
    }

    /**
     * Hiển thị danh sách tiến trình học tập (cho admin)
     */
    public function index(Request $request)
    {
        // Lấy dữ liệu từ bảng diemthi thực tế
        $query = DiemThi::with(['sinhVien', 'monHoc', 'lopHoc']);

        // Lọc theo sinh viên
        if ($request->has('maSV') && $request->maSV) {
            $query->where('MaSV', 'like', '%' . $request->maSV . '%');
        }

        // Lọc theo môn học
        if ($request->has('maMH') && $request->maMH) {
            $query->where('MaMH', $request->maMH);
        }

        // Lọc theo lớp
        if ($request->has('maLop') && $request->maLop) {
            $query->where('MaLop', $request->maLop);
        }

        // Lọc theo trạng thái (dựa trên điểm)
        if ($request->has('trangThai') && $request->trangThai) {
            switch ($request->trangThai) {
                case 'DaHoanThanh':
                    $query->where('DiemTong', '>=', 5.0);
                    break;
                case 'ChuaHoanThanh':
                    $query->where('DiemTong', '<', 5.0)->whereNotNull('DiemTong');
                    break;
                case 'DangHoc':
                    $query->whereNull('DiemTong');
                    break;
                case 'DangKy':
                    $query->whereNull('DiemTong');
                    break;
                case 'ChuaDangKy':
                    // Không lọc theo điểm, sẽ được xử lý sau khi chuyển đổi dữ liệu
                    break;
            }
        }

        $diemThis = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Chuyển đổi thành dữ liệu tiến trình
        $tienTrinh = $diemThis->getCollection()->map(function ($diemThi) {
            // Lấy thông tin chương trình đào tạo từ môn học
            $maChuongTrinh = null;
            if ($diemThi->monHoc) {
                // Tìm chương trình đào tạo thông qua môn học
                $chuongTrinhMonHoc = ChuongTrinhMonHoc::where('MaMH', $diemThi->MaMH)->first();
                if ($chuongTrinhMonHoc) {
                    $maChuongTrinh = $chuongTrinhMonHoc->MaChuongTrinh;
                }
            }

            // Xác định trạng thái học tập
            $trangThai = $this->xacDinhTrangThai(
                $diemThi->DiemTong, 
                $diemThi->MaSV, 
                $diemThi->MaMH, 
                $diemThi->MaLop, 
                $maChuongTrinh
            );

            return (object) [
                'id' => $diemThi->id,
                'MaSV' => $diemThi->MaSV,
                'MaMH' => $diemThi->MaMH,
                'MaLop' => $diemThi->MaLop,
                'DiemTong' => $diemThi->DiemTong,
                'DiemLyThuyet' => $diemThi->DiemLyThuyet,
                'DiemThucHanh' => $diemThi->DiemThucHanh,
                'DiemDuAn' => $diemThi->DiemDuAn,
                'GhiChu' => $diemThi->GhiChu,
                'TrangThai' => $trangThai,
                'XepLoai' => $this->xacDinhXepLoai($diemThi->DiemTong, $maChuongTrinh),
                'NgayHoanThanh' => $trangThai === 'DaHoanThanh' ? now() : null,
                'sinhVien' => $diemThi->sinhVien,
                'monHoc' => $diemThi->monHoc,
                'lopHoc' => $diemThi->lopHoc,
                'SoTinChi' => $diemThi->monHoc->GioTrienKhai ?? 0,
                'MaChuongTrinh' => $maChuongTrinh
            ];
        });

        // Lọc theo trạng thái sau khi chuyển đổi dữ liệu
        if ($request->has('trangThai') && $request->trangThai === 'ChuaDangKy') {
            $tienTrinh = $tienTrinh->filter(function ($item) {
                return $item->TrangThai === 'ChuaDangKy';
            });
        }

        // Tạo paginator mới với dữ liệu đã chuyển đổi
        $tienTrinh = new \Illuminate\Pagination\LengthAwarePaginator(
            $tienTrinh,
            $diemThis->total(),
            $diemThis->perPage(),
            $diemThis->currentPage(),
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        $monHocs = MonHoc::all();
        $lopHocs = LopHoc::all();

        return view('tien_trinh_hoc_tap.index', compact('tienTrinh', 'monHocs', 'lopHocs'));
    }

    /**
     * Cập nhật tiến trình học tập từ điểm thi thực tế
     */
    public function capNhatTuDiemThi()
    {
        return redirect()->back()->with('info', 'Hệ thống hiện tại đã lấy dữ liệu trực tiếp từ bảng điểm thi. Không cần cập nhật thủ công.');
    }

    /**
     * Thống kê tiến trình học tập từ dữ liệu thực
     */
    public function thongKe(Request $request)
    {
        // Lấy dữ liệu từ bảng điểm thi thực tế
        $diemThis = DiemThi::with(['sinhVien', 'monHoc', 'lopHoc']);

        // Lọc theo môn học
        if ($request->has('maMH') && $request->maMH) {
            $diemThis = $diemThis->where('MaMH', $request->maMH);
        }

        // Lọc theo lớp
        if ($request->has('maLop') && $request->maLop) {
            $diemThis = $diemThis->where('MaLop', $request->maLop);
        }

        $diemThis = $diemThis->get();

        // Thống kê theo trạng thái (dựa trên điểm)
        $thongKeTrangThai = collect([
            'DaHoanThanh' => $diemThis->where('DiemTong', '>=', 5.0)->count(),
            'ChuaHoanThanh' => $diemThis->where('DiemTong', '<', 5.0)->whereNotNull('DiemTong')->count(),
            'DangHoc' => $diemThis->whereNull('DiemTong')->count(),
        ]);

        // Thống kê theo xếp loại
        $thongKeXepLoai = collect([
            'Giỏi' => $diemThis->where('DiemTong', '>=', 8.5)->count(),
            'Khá' => $diemThis->where('DiemTong', '>=', 7.0)->where('DiemTong', '<', 8.5)->count(),
            'Trung bình' => $diemThis->where('DiemTong', '>=', 5.0)->where('DiemTong', '<', 7.0)->count(),
            'Yếu' => $diemThis->where('DiemTong', '<', 5.0)->whereNotNull('DiemTong')->count(),
            'Chưa xếp loại' => $diemThis->whereNull('DiemTong')->count(),
        ]);

        // Thống kê theo môn học
        $thongKeMonHoc = $diemThis->groupBy('MaMH')
            ->map(function ($group) {
                $monHoc = $group->first()->monHoc;
                return [
                    'tenMonHoc' => $monHoc->TenMH ?? 'Không xác định',
                    'tongSo' => $group->count(),
                    'daHoanThanh' => $group->where('DiemTong', '>=', 5.0)->count(),
                    'diemTrungBinh' => $group->whereNotNull('DiemTong')->avg('DiemTong') ?? 0
                ];
            });

        // Lấy dữ liệu từ bảng thực tế
        $monHocs = MonHoc::all();
        $lopHocs = LopHoc::all();
        $chuongTrinhs = ChuongTrinh::all();

        // Thống kê tổng quan
        $tongQuan = $this->layDuLieuThongKe();

        return view('tien_trinh_hoc_tap.thong_ke', compact(
            'thongKeTrangThai',
            'thongKeXepLoai',
            'thongKeMonHoc',
            'chuongTrinhs',
            'lopHocs',
            'monHocs',
            'tongQuan'
        ));
    }

    /**
     * Xuất báo cáo Excel
     */
    public function xuatBaoCao(Request $request)
    {
        $query = DiemThi::with(['sinhVien', 'monHoc', 'lopHoc']);

        if ($request->has('maSV') && $request->maSV) {
            $query->where('MaSV', $request->maSV);
        }

        $diemThis = $query->get();

        // Chuyển đổi thành dữ liệu tiến trình
        $tienTrinh = $diemThis->map(function ($diemThi) {
            // Lấy thông tin chương trình đào tạo từ môn học
            $maChuongTrinh = null;
            if ($diemThi->monHoc) {
                // Tìm chương trình đào tạo thông qua môn học
                $chuongTrinhMonHoc = ChuongTrinhMonHoc::where('MaMH', $diemThi->MaMH)->first();
                if ($chuongTrinhMonHoc) {
                    $maChuongTrinh = $chuongTrinhMonHoc->MaChuongTrinh;
                }
            }

            // Xác định trạng thái học tập
            $trangThai = $this->xacDinhTrangThai(
                $diemThi->DiemTong, 
                $diemThi->MaSV, 
                $diemThi->MaMH, 
                $diemThi->MaLop, 
                $maChuongTrinh
            );

            return (object) [
                'id' => $diemThi->id,
                'MaSV' => $diemThi->MaSV,
                'MaMH' => $diemThi->MaMH,
                'MaLop' => $diemThi->MaLop,
                'DiemTong' => $diemThi->DiemTong,
                'DiemLyThuyet' => $diemThi->DiemLyThuyet,
                'DiemThucHanh' => $diemThi->DiemThucHanh,
                'DiemDuAn' => $diemThi->DiemDuAn,
                'GhiChu' => $diemThi->GhiChu,
                'TrangThai' => $trangThai,
                'XepLoai' => $this->xacDinhXepLoai($diemThi->DiemTong, $maChuongTrinh),
                'NgayHoanThanh' => $trangThai === 'DaHoanThanh' ? now() : null,
                'sinhVien' => $diemThi->sinhVien,
                'monHoc' => $diemThi->monHoc,
                'lopHoc' => $diemThi->lopHoc,
                'SoTinChi' => $diemThi->monHoc->GioTrienKhai ?? 0,
                'MaChuongTrinh' => $maChuongTrinh
            ];
        });

        // Tạo file Excel
        $fileName = 'bao_cao_tien_trinh_hoc_tap_' . date('Y-m-d_H-i-s') . '.xlsx';
        
        return Excel::download(new TienTrinhHocTapExport($tienTrinh), $fileName);
    }

 

    /**
     * Xác định trạng thái học tập dựa trên điểm và thời khóa biểu
     */
    private function xacDinhTrangThai($diemTong, $maSV, $maMH, $maLop, $maChuongTrinh = null)
    {
        // 1) Ưu tiên trạng thái theo điểm tổng
        if ($diemTong !== null) {
            return $diemTong >= 5.0 ? 'DaHoanThanh' : 'ChuaHoanThanh';
        }

        // 2) Xác định lớp hiện tại nếu chưa có
        if (empty($maLop)) {
            $maLop = DB::table('danhsachsv')
                ->where('MaSV', $maSV)
                ->value('MaLop');
        }

        // 3) Nếu có lớp, kiểm tra môn có nằm trong TKB hiện tại của lớp hay không
        if (!empty($maLop)) {
            $tkbRecord = tkb::where('MaLop', $maLop)
                ->orderByDesc('NgayHoc')
                ->first();

            if ($tkbRecord) {
                $monTrongTKB = danhsachmonhoc::where('MaHK', $tkbRecord->MaHK)
                    ->where('MaMH', $maMH)
                    ->exists();

                if ($monTrongTKB) {
                    return 'DangHoc';
                }
            }
        }

        // 4) Nếu không nằm trong TKB, nhưng thuộc CTĐT => Đã đăng ký; nếu không => Chưa đăng ký
        $coTrongChuongTrinh = ChuongTrinhMonHoc::where('MaMH', $maMH)
            ->when($maChuongTrinh, function ($q) use ($maChuongTrinh) {
                $q->where('MaChuongTrinh', $maChuongTrinh);
            })
            ->exists();

        return $coTrongChuongTrinh ? 'DangKy' : 'ChuaDangKy';
    }

    private function xacDinhXepLoai($diemTong, $maChuongTrinh = null)
    {
        if ($diemTong === null) {
            return 'Chưa xếp loại';
        }

        // Lấy tiêu chí xếp loại từ chương trình đào tạo nếu có
        if ($maChuongTrinh) {
            $tieuChi = TieuChiXepLoai::where('MaChuongTrinh', $maChuongTrinh)
                ->orderBy('DiemTu')
                ->get();

            foreach ($tieuChi as $tc) {
                if ($diemTong >= $tc->DiemTu && $diemTong < $tc->DiemDen) {
                    return $tc->XepLoai;
                }
            }
        }

        // Xếp loại mặc định nếu không có tiêu chí
        if ($diemTong >= 8.5) {
            return 'Giỏi';
        } elseif ($diemTong >= 7.0) {
            return 'Khá';
        } elseif ($diemTong >= 5.0) {
            return 'Đạt';
        } else {
            return 'Chưa đạt';
        }
    }

    private function xacDinhXepLoaiChung($tienTrinh)
    {
        // Chỉ tính điểm trung bình cho các môn đã hoàn thành
        $monHoanThanh = $tienTrinh->where('TrangThai', 'DaHoanThanh');
        $diemTrungBinh = $monHoanThanh->avg('DiemTong');
        
        if ($diemTrungBinh === null || $monHoanThanh->isEmpty()) {
            return 'Chưa xếp loại';
        }

        // Lấy mã chương trình từ môn học đầu tiên (nếu có)
        $maChuongTrinh = $tienTrinh->first()->MaChuongTrinh;
        
        if ($maChuongTrinh) {
            $tieuChi = TieuChiXepLoai::where('MaChuongTrinh', $maChuongTrinh)
                ->orderBy('DiemTu')
                ->get();

            foreach ($tieuChi as $tc) {
                if ($diemTrungBinh >= $tc->DiemTu && $diemTrungBinh < $tc->DiemDen) {
                    return $tc->XepLoai;
                }
            }
        }

        // Xếp loại mặc định nếu không có tiêu chí
        if ($diemTrungBinh >= 8.5) {
            return 'Giỏi';
        } elseif ($diemTrungBinh >= 7.0) {
            return 'Khá';
        } elseif ($diemTrungBinh >= 5.0) {
            return 'Đạt';
        } else {
            return 'Chưa đạt';
        }
    }

    /**
     * Lấy dữ liệu thống kê từ bảng thực tế
     */
    public function layDuLieuThongKe()
    {
        // Lấy dữ liệu từ các bảng thực tế
        $tongSinhVien = SinhVien::count();
        $tongMonHoc = MonHoc::count();
        $tongLopHoc = LopHoc::count();
        $tongDiemThi = DiemThi::count();
        
        // Thống kê điểm thi
        $diemThiStats = DiemThi::selectRaw('
            COUNT(*) as tong_so,
            COUNT(CASE WHEN DiemTong >= 5.0 THEN 1 END) as dat,
            COUNT(CASE WHEN DiemTong < 5.0 THEN 1 END) as khong_dat,
            AVG(DiemTong) as diem_trung_binh
        ')->first();

        return [
            'tongSinhVien' => $tongSinhVien,
            'tongMonHoc' => $tongMonHoc,
            'tongLopHoc' => $tongLopHoc,
            'tongDiemThi' => $tongDiemThi,
            'diemThiStats' => $diemThiStats
        ];
    }

    /**
     * Lấy danh sách sinh viên trong lớp từ bảng danhsachsv
     */
    public function laySinhVienTrongLop($maLop)
    {
        return DB::table('danhsachsv')
            ->join('sinhvien', 'danhsachsv.MaSV', '=', 'sinhvien.MaSV')
            ->where('danhsachsv.MaLop', $maLop)
            ->select('sinhvien.*')
            ->get();
    }

    /**
     * Lấy thông tin môn học từ bảng danhsachmonhoc
     */
    public function layMonHocTheoHocKy($maHK)
    {
        return DB::table('DanhSachMH')
            ->join('monhoc', 'DanhSachMH.MaMH', '=', 'monhoc.MaMH')
            ->where('DanhSachMH.MaHK', $maHK)
            ->select('monhoc.*', 'DanhSachMH.SttMH')
            ->orderBy('DanhSachMH.SttMH')
            ->get();
    }

    /**
     * Lấy thông tin giảng dạy từ bảng giangday
     */
    public function layGiangDayTheoGiaoVien($maGV)
    {
        return DB::table('giangday')
            ->join('monhoc', 'giangday.MaMH', '=', 'monhoc.MaMH')
            ->join('lophoc', 'giangday.MaLop', '=', 'lophoc.MaLop')
            ->where('giangday.MaGV', $maGV)
            ->select('giangday.*', 'monhoc.TenMH', 'lophoc.TenLop')
            ->get();
    }
}