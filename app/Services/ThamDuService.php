<?php

namespace App\Services;

use App\Models\DiemThi;
use App\Models\TKB;
use App\Models\GiangDay;
use App\Models\LopHoc;
use App\Models\MonHoc;
use App\Models\HocKi;
use App\Models\danhsachsv;
use App\Models\sinhvien;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ThamDuService
{
    /**
     * Tính tỷ lệ tham dự của sinh viên dựa trên dữ liệu hiện có
     * Logic: Sử dụng số lần cập nhật điểm và lịch học để ước tính tham dự
     */
    public function tinhTyLeThamDu($maSV, $maLop, $maMH = null)
    {
        // Lấy lịch học từ TKB
        $lichHoc = TKB::where('MaLop', $maLop);
        if ($maMH) {
            $lichHoc = $lichHoc->whereHas('lopHoc.giangViens', function($query) use ($maMH) {
                $query->where('MaMH', $maMH);
            });
        }
        $lichHoc = $lichHoc->get();

        // Lấy số lần có điểm cập nhật (proxy cho sự tham dự)
        $diemQuery = DiemThi::where('MaSV', $maSV)->where('MaLop', $maLop);
        if ($maMH) {
            $diemQuery = $diemQuery->where('MaMH', $maMH);
        }
        $soLanCoDiem = $diemQuery->whereNotNull('updated_at')->count();

        // Ước tính số buổi học dự kiến
        $soBuoiHocDuKien = $lichHoc->count();
        
        if ($soBuoiHocDuKien == 0) {
            return 0;
        }

        // Tính tỷ lệ tham dự (tối đa 100%)
        $tyLeThamDu = min(($soLanCoDiem / $soBuoiHocDuKien) * 100, 100);
        
        return round($tyLeThamDu, 2);
    }

    /**
     * Thống kê tham dự theo lớp
     */
    public function thongKeThamDuTheoLop($maLop)
    {
        // Lấy danh sách sinh viên trong lớp
        $danhSachSV = danhsachsv::with('sinhVien')
            ->where('MaLop', $maLop)
            ->get();

        $thongKe = collect();

        foreach ($danhSachSV as $sv) {
            $tyLeThamDu = $this->tinhTyLeThamDu($sv->MaSV, $maLop);
            
            $thongKe->push([
                'MaSV' => $sv->MaSV,
                'HoTen' => $sv->sinhVien->HoTen ?? 'N/A',
                'TyLeThamDu' => $tyLeThamDu,
                'XepLoaiThamDu' => $this->xepLoaiThamDu($tyLeThamDu),
                'SoLanCoDiem' => DiemThi::where('MaSV', $sv->MaSV)
                    ->where('MaLop', $maLop)
                    ->whereNotNull('updated_at')
                    ->count(),
                'TongSoBuoiHoc' => TKB::where('MaLop', $maLop)->count()
            ]);
        }

        return $thongKe->sortByDesc('TyLeThamDu');
    }

    /**
     * Thống kê tham dự theo môn học
     */
    public function thongKeThamDuTheoMon($maLop, $maMH)
    {
        // Lấy danh sách sinh viên trong lớp
        $danhSachSV = danhsachsv::with('sinhVien')
            ->where('MaLop', $maLop)
            ->get();

        $thongKe = collect();

        foreach ($danhSachSV as $sv) {
            $tyLeThamDu = $this->tinhTyLeThamDu($sv->MaSV, $maLop, $maMH);
            
            $thongKe->push([
                'MaSV' => $sv->MaSV,
                'HoTen' => $sv->sinhVien->HoTen ?? 'N/A',
                'TyLeThamDu' => $tyLeThamDu,
                'XepLoaiThamDu' => $this->xepLoaiThamDu($tyLeThamDu),
                'DiemTong' => DiemThi::where('MaSV', $sv->MaSV)
                    ->where('MaLop', $maLop)
                    ->where('MaMH', $maMH)
                    ->value('DiemTong'),
                'SoLanCoDiem' => DiemThi::where('MaSV', $sv->MaSV)
                    ->where('MaLop', $maLop)
                    ->where('MaMH', $maMH)
                    ->whereNotNull('updated_at')
                    ->count()
            ]);
        }

        return $thongKe->sortByDesc('TyLeThamDu');
    }

    /**
     * Thống kê tổng quan tham dự của lớp
     */
    public function thongKeTongQuanLop($maLop)
    {
        $danhSachSV = danhsachsv::where('MaLop', $maLop)->get();
        $tongSV = $danhSachSV->count();
        
        if ($tongSV == 0) {
            return [
                'tong_sinh_vien' => 0,
                'tham_du_tot' => 0,
                'tham_du_trung_binh' => 0,
                'tham_du_yeu' => 0,
                'ty_le_tham_du_tb' => 0
            ];
        }

        $thamDuTot = 0;
        $thamDuTrungBinh = 0;
        $thamDuYeu = 0;
        $tongTyLeThamDu = 0;

        foreach ($danhSachSV as $sv) {
            $tyLeThamDu = $this->tinhTyLeThamDu($sv->MaSV, $maLop);
            $tongTyLeThamDu += $tyLeThamDu;

            if ($tyLeThamDu >= 80) {
                $thamDuTot++;
            } elseif ($tyLeThamDu >= 60) {
                $thamDuTrungBinh++;
            } else {
                $thamDuYeu++;
            }
        }

        return [
            'tong_sinh_vien' => $tongSV,
            'tham_du_tot' => $thamDuTot,
            'tham_du_trung_binh' => $thamDuTrungBinh,
            'tham_du_yeu' => $thamDuYeu,
            'ty_le_tham_du_tb' => round($tongTyLeThamDu / $tongSV, 2)
        ];
    }

    /**
     * Lấy danh sách lớp mà giảng viên đang dạy
     */
    public function layDanhSachLopGiangVien($maGV)
    {
        return GiangDay::with(['lopHoc', 'monHoc'])
            ->where('MaGV', $maGV)
            ->get()
            ->groupBy('MaLop')
            ->map(function ($group) {
                $lopHoc = $group->first()->lopHoc;
                $monHocs = $group->pluck('monHoc');
                
                return [
                    'MaLop' => $lopHoc->MaLop ?? 'N/A',
                    'TenLop' => $lopHoc->TenLop ?? 'N/A',
                    'MonHocs' => $monHocs,
                    'SoMonHoc' => $monHocs->count(),
                    'NgayBatDau' => $group->first()->NgayBatDau,
                    'NgayKetThuc' => $group->first()->NgayKetThuc
                ];
            });
    }

    /**
     * Xếp loại tham dự
     */
    private function xepLoaiThamDu($tyLeThamDu)
    {
        if ($tyLeThamDu >= 90) {
            return 'Xuất sắc';
        } elseif ($tyLeThamDu >= 80) {
            return 'Tốt';
        } elseif ($tyLeThamDu >= 70) {
            return 'Khá';
        } elseif ($tyLeThamDu >= 60) {
            return 'Trung bình';
        } else {
            return 'Yếu';
        }
    }

    /**
     * Lấy chi tiết tham dự của sinh viên
     */
    public function layChiTietThamDuSV($maSV, $maLop, $maMH = null)
    {
        $sinhVien = sinhvien::find($maSV);
        $lopHoc = LopHoc::find($maLop);
        
        // Lấy lịch học
        $lichHoc = TKB::where('MaLop', $maLop);
        if ($maMH) {
            $lichHoc = $lichHoc->whereHas('lopHoc.giangViens', function($query) use ($maMH) {
                $query->where('MaMH', $maMH);
            });
        }
        $lichHoc = $lichHoc->get();

        // Lấy lịch sử điểm
        $diemQuery = DiemThi::where('MaSV', $maSV)->where('MaLop', $maLop);
        if ($maMH) {
            $diemQuery = $diemQuery->where('MaMH', $maMH);
        }
        $lichSuDiem = $diemQuery->orderBy('updated_at', 'desc')->get();

        $tyLeThamDu = $this->tinhTyLeThamDu($maSV, $maLop, $maMH);

        return [
            'sinh_vien' => $sinhVien,
            'lop_hoc' => $lopHoc,
            'ty_le_tham_du' => $tyLeThamDu,
            'xep_loai_tham_du' => $this->xepLoaiThamDu($tyLeThamDu),
            'lich_hoc' => $lichHoc,
            'lich_su_diem' => $lichSuDiem,
            'so_buoi_hoc_du_kien' => $lichHoc->count(),
            'so_lan_co_diem' => $lichSuDiem->whereNotNull('updated_at')->count()
        ];
    }

    /**
     * Xuất báo cáo tham dự Excel
     */
    public function xuatBaoCaoThamDu($maLop, $maMH = null)
    {
        if ($maMH) {
            $thongKe = $this->thongKeThamDuTheoMon($maLop, $maMH);
            $tenFile = "ThamDu_Lop_{$maLop}_Mon_{$maMH}_" . date('Y-m-d') . ".xlsx";
        } else {
            $thongKe = $this->thongKeThamDuTheoLop($maLop);
            $tenFile = "ThamDu_Lop_{$maLop}_" . date('Y-m-d') . ".xlsx";
        }

        return [
            'data' => $thongKe,
            'filename' => $tenFile
        ];
    }
}
