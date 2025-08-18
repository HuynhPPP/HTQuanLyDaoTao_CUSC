<?php

namespace App\Http\Controllers\Front\SinhVien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\sinhvien;
use App\Models\LdapAccount;
use App\Models\DiemThi;
use App\Models\ChuongTrinhMonHoc;
use App\Models\TieuChiXepLoai;
use App\Models\danhsachmonhoc;
use App\Models\tkb;
use App\Models\lophoc;
use App\Models\HinhThucDanhGia;

class TienTrinhCaNhanController extends Controller
{
    public function index(Request $request)
    {
        $username = session('user');
        if (!$username) {
            return redirect()->route('login')->with('error', 'Bạn chưa đăng nhập!');
        }

        $ldap = LdapAccount::where('username', $username)->first();
        if (!$ldap) {
            return redirect()->back()->with('error', 'Không tìm thấy tài khoản');
        }

        $maSV = $ldap->MaTaiKhoan;
        $sinhVien = sinhvien::where('MaSV', $maSV)->firstOrFail();

        // Xác định chương trình đào tạo từ lớp của sinh viên (ưu tiên)
        $maLop = DB::table('danhsachsv')->where('MaSV', $maSV)->value('MaLop');
        $maChuongTrinh = null;
        if ($maLop) {
            $lop = lophoc::find($maLop);
            $maChuongTrinh = $lop->MaChuongTrinh ?? null;
        }
        if (!$maChuongTrinh) {
            $monIds = DiemThi::where('MaSV', $maSV)->pluck('MaMH')->unique();
            $ctmh = ChuongTrinhMonHoc::whereIn('MaMH', $monIds)->first();
            $maChuongTrinh = $ctmh->MaChuongTrinh ?? null;
        }

        // Môn học trong CTĐT
        $monHocTrongChuongTrinh = collect();
        if ($maChuongTrinh) {
            $monHocTrongChuongTrinh = ChuongTrinhMonHoc::where('MaChuongTrinh', $maChuongTrinh)
                ->with('monHoc')
                ->get();
        }

        // Điểm thi của SV
        $diemThis = DiemThi::with(['monHoc', 'lopHoc'])
            ->where('MaSV', $maSV)
            ->get();

        // Cấu hình hình thức đánh giá
        $dsHinhThuc = $maChuongTrinh
            ? HinhThucDanhGia::where('MaChuongTrinh', $maChuongTrinh)->orderBy('id')->get()
            : collect();

        $tienTrinh = collect();
        foreach ($monHocTrongChuongTrinh as $monHocCT) {
            $diemThi = $diemThis->where('MaMH', $monHocCT->MaMH)->first();

            // Tính tổng điểm theo CTĐT
            $tongDiemTinhLai = null;
            if ($diemThi) {
                if ($dsHinhThuc->count() > 0) {
                    $mapField = function (string $hinhThuc): ?string {
                        $lh = mb_strtolower($hinhThuc, 'UTF-8');
                        if (strpos($lh, 'lý thuyết') !== false || strpos($lh, 'ly thuyet') !== false) return 'DiemLyThuyet';
                        if (strpos($lh, 'thực hành') !== false || strpos($lh, 'thuc hanh') !== false) return 'DiemThucHanh';
                        if (strpos($lh, 'dự án') !== false || strpos($lh, 'du an') !== false) return 'DiemDuAn';
                        return null;
                    };
                    $sum = 0.0;
                    foreach ($dsHinhThuc as $ht) {
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

            $trangThai = $this->determineTrangThai(
                $tongDiemTinhLai,
                $maSV,
                $monHocCT->MaMH,
                $diemThi ? $diemThi->MaLop : $maLop,
                $maChuongTrinh
            );

            $tienTrinh->push((object) [
                'MaSV' => $maSV,
                'MaMH' => $monHocCT->MaMH,
                'MaLop' => $diemThi ? $diemThi->MaLop : $maLop,
                'DiemTong' => $diemThi ? $diemThi->DiemTong : null,
                'TongDiemTinhLai' => $tongDiemTinhLai,
                'DiemLyThuyet' => $diemThi ? $diemThi->DiemLyThuyet : null,
                'DiemThucHanh' => $diemThi ? $diemThi->DiemThucHanh : null,
                'DiemDuAn' => $diemThi ? $diemThi->DiemDuAn : null,
                'GhiChu' => $diemThi ? $diemThi->GhiChu : null,
                'TrangThai' => $trangThai,
                'XepLoai' => $this->determineXepLoai($tongDiemTinhLai, $maChuongTrinh),
                'NgayHoanThanh' => $trangThai === 'DaHoanThanh' ? now() : null,
                'monHoc' => $monHocCT->monHoc,
                'lopHoc' => $diemThi ? $diemThi->lopHoc : null,
                'SoTinChi' => $monHocCT->monHoc->GioTrienKhai ?? 0,
                'MaChuongTrinh' => $maChuongTrinh
            ]);
        }

        $thongKe = [
            'tongMonHoc' => $tienTrinh->count(),
            'monDaHoanThanh' => $tienTrinh->where('TrangThai', 'DaHoanThanh')->count(),
            'monDangHoc' => $tienTrinh->where('TrangThai', 'DangHoc')->count(),
            'monDaDangKy' => $tienTrinh->where('TrangThai', 'DangKy')->count(),
            'monChuaDangKy' => $tienTrinh->where('TrangThai', 'ChuaDangKy')->count(),
            'tongTinChi' => $tienTrinh->where('TrangThai', 'DaHoanThanh')->sum('SoTinChi'),
            'diemTrungBinh' => $tienTrinh->whereNotNull('TongDiemTinhLai')->avg('TongDiemTinhLai')
                ?? ($tienTrinh->whereNotNull('DiemTong')->avg('DiemTong') ?? 0),
            'xepLoai' => $this->determineXepLoaiChung($tienTrinh)
        ];

        return view('frontend.sinhvien.tien_trinh_hoc_tap.index', compact('sinhVien', 'tienTrinh', 'thongKe'));
    }

    private function determineTrangThai($diemTong, $maSV, $maMH, $maLop, $maChuongTrinh = null)
    {
        if ($diemTong !== null) {
            return $diemTong >= 5.0 ? 'DaHoanThanh' : 'ChuaHoanThanh';
        }

        if (empty($maLop)) {
            $maLop = DB::table('danhsachsv')->where('MaSV', $maSV)->value('MaLop');
        }

        if (!empty($maLop)) {
            $tkbRecord = tkb::where('MaLop', $maLop)->orderByDesc('NgayHoc')->first();
            if ($tkbRecord) {
                $monTrongTKB = danhsachmonhoc::where('MaHK', $tkbRecord->MaHK)
                    ->where('MaMH', $maMH)
                    ->exists();
                if ($monTrongTKB) {
                    return 'DangHoc';
                }
            }
        }

        $coTrongCT = ChuongTrinhMonHoc::where('MaMH', $maMH)
            ->when($maChuongTrinh, function ($q) use ($maChuongTrinh) {
                $q->where('MaChuongTrinh', $maChuongTrinh);
            })
            ->exists();
        return $coTrongCT ? 'DangKy' : 'ChuaDangKy';
    }

    private function determineXepLoai($diemTong, $maChuongTrinh = null)
    {
        if ($diemTong === null) {
            return 'Chưa xếp loại';
        }
        if ($maChuongTrinh) {
            $tieuChi = TieuChiXepLoai::where('MaChuongTrinh', $maChuongTrinh)->orderBy('DiemTu')->get();
            foreach ($tieuChi as $tc) {
                if ($diemTong >= $tc->DiemTu && $diemTong < $tc->DiemDen) {
                    return $tc->XepLoai;
                }
            }
        }
        if ($diemTong >= 8.5) return 'Giỏi';
        if ($diemTong >= 7.0) return 'Khá';
        if ($diemTong >= 5.0) return 'Đạt';
        return 'Chưa đạt';
    }

    private function determineXepLoaiChung($tienTrinh)
    {
        $monHoanThanh = $tienTrinh->where('TrangThai', 'DaHoanThanh');
        $diemTB = $monHoanThanh->avg(function ($m) {
            return $m->TongDiemTinhLai ?? $m->DiemTong;
        });
        if ($diemTB === null || $monHoanThanh->isEmpty()) return 'Chưa xếp loại';
        $maCT = $tienTrinh->first()->MaChuongTrinh;
        if ($maCT) {
            $tieuChi = TieuChiXepLoai::where('MaChuongTrinh', $maCT)->orderBy('DiemTu')->get();
            foreach ($tieuChi as $tc) {
                if ($diemTB >= $tc->DiemTu && $diemTB < $tc->DiemDen) return $tc->XepLoai;
            }
        }
        if ($diemTB >= 8.5) return 'Giỏi';
        if ($diemTB >= 7.0) return 'Khá';
        if ($diemTB >= 5.0) return 'Đạt';
        return 'Chưa đạt';
    }
}


