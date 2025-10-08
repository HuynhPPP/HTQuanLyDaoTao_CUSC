<?php

namespace App\Services;

use App\Models\DiemThi;
use App\Models\lophoc;
use App\Models\TieuChiXepLoai;
use Illuminate\Support\Facades\DB;

class RankingService
{
    /**
     * Xếp hạng sinh viên theo lớp
     */
    public function xepHangSinhVienTheoLop($MaLop)
    {
        $lop = lophoc::with('danhSachSinhVien.sinhVien')->find($MaLop);
        
        if (!$lop) {
            return collect();
        }

        $bangXepHang = collect();

        foreach ($lop->danhSachSinhVien as $dsSV) {
            $sinhVien = $dsSV->sinhVien;
            
            // Tính điểm trung bình của sinh viên trong lớp
            $diemTB = DiemThi::where('MaSV', $sinhVien->MaSV)
                           ->where('MaLop', $MaLop)
                           ->avg('DiemTong');
            
            // Đếm số môn đã có điểm
            $soMonCoDiem = DiemThi::where('MaSV', $sinhVien->MaSV)
                                ->where('MaLop', $MaLop)
                                ->whereNotNull('DiemTong')
                                ->count();

            if ($soMonCoDiem > 0) {
                $bangXepHang->push([
                    'MaSV' => $sinhVien->MaSV,
                    'HoTen' => $sinhVien->HoTen,
                    'DiemTB' => round($diemTB, 2),
                    'SoMonCoDiem' => $soMonCoDiem,
                    'XepLoai' => $this->xacDinhXepLoai($diemTB)
                ]);
            }
        }

        return $bangXepHang->sortByDesc('DiemTB')->values();
    }

    /**
     * Top sinh viên xuất sắc toàn trường
     */
    public function topSinhVienXuatSac($limit = 10)
    {
        return DiemThi::with('sinhVien')
                     ->selectRaw('MaSV, AVG(DiemTong) as DiemTB, COUNT(*) as SoMon')
                     ->whereNotNull('DiemTong')
                     ->groupBy('MaSV')
                     ->having('SoMon', '>=', 3) // Ít nhất 3 môn có điểm
                     ->orderByDesc('DiemTB')
                     ->limit($limit)
                     ->get()
                     ->map(function($item) {
                         $item->XepLoai = $this->xacDinhXepLoai($item->DiemTB);
                         return $item;
                     });
    }

    /**
     * Xếp hạng theo khoa/chương trình
     */
    public function xepHangTheoChuongTrinh($MaChuongTrinh)
    {
        return DiemThi::with(['sinhVien', 'lopHoc'])
                     ->join('lophoc', 'diemthi.MaLop', '=', 'lophoc.MaLop')
                     ->where('lophoc.MaChuongTrinh', $MaChuongTrinh)
                     ->selectRaw('diemthi.MaSV, AVG(diemthi.DiemTong) as DiemTB, COUNT(*) as SoMon')
                     ->whereNotNull('diemthi.DiemTong')
                     ->groupBy('diemthi.MaSV')
                     ->having('SoMon', '>=', 2)
                     ->orderByDesc('DiemTB')
                     ->get()
                     ->map(function($item) {
                         $item->XepLoai = $this->xacDinhXepLoai($item->DiemTB);
                         return $item;
                     });
    }

    /**
     * Thống kê xếp hạng theo lớp
     */
    public function thongKeXepHangTheoLop($MaLop)
    {
        $bangXepHang = $this->xepHangSinhVienTheoLop($MaLop);
        
        $thongKe = [
            'tong_sinh_vien' => $bangXepHang->count(),
            'xuat_sac' => $bangXepHang->where('XepLoai', 'Xuất sắc')->count(),
            'gioi' => $bangXepHang->where('XepLoai', 'Giỏi')->count(),
            'kha' => $bangXepHang->where('XepLoai', 'Khá')->count(),
            'trung_binh' => $bangXepHang->where('XepLoai', 'Trung bình')->count(),
            'yeu' => $bangXepHang->where('XepLoai', 'Yếu')->count(),
            'diem_tb_lop' => $bangXepHang->avg('DiemTB')
        ];

        return $thongKe;
    }

    /**
     * Xác định xếp loại học lực
     */
    private function xacDinhXepLoai($diemTB)
    {
        if ($diemTB >= 8.5) return 'Xuất sắc';
        if ($diemTB >= 7.0) return 'Giỏi';
        if ($diemTB >= 5.5) return 'Khá';
        if ($diemTB >= 4.0) return 'Trung bình';
        return 'Yếu';
    }

    /**
     * So sánh hiệu suất giữa các lớp
     */
    public function soSanhHieuSuatCacLop()
    {
        return lophoc::with('danhSachSinhVien')
                    ->get()
                    ->map(function($lop) {
                        $bangXepHang = $this->xepHangSinhVienTheoLop($lop->MaLop);
                        return [
                            'MaLop' => $lop->MaLop,
                            'TenLop' => $lop->TenLop,
                            'SoSinhVien' => $bangXepHang->count(),
                            'DiemTBLop' => $bangXepHang->avg('DiemTB'),
                            'TyLeXuatSac' => $bangXepHang->where('XepLoai', 'Xuất sắc')->count() / max($bangXepHang->count(), 1) * 100
                        ];
                    })
                    ->sortByDesc('DiemTBLop');
    }
}
