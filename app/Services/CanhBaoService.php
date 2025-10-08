<?php

namespace App\Services;

use App\Models\DiemThi;
use App\Models\sinhvien;
use App\Models\lophoc;
use App\Models\monhoc;
use App\Models\ChuongTrinh;
use Illuminate\Support\Facades\DB;

class CanhBaoService
{
    /**
     * Phát hiện sinh viên có điểm thấp (nguy cơ rớt môn)
     */
    public function phatHienDiemThap($nguongDiem = 4.0)
    {
        $sinhVienDiemThap = DiemThi::with(['sinhVien', 'monHoc', 'lopHoc'])
            ->where('DiemTong', '<', $nguongDiem)
            ->where('DiemTong', '>', 0) // Loại bỏ điểm 0 (chưa có điểm)
            ->get();

        $canhBaoList = collect();

        foreach ($sinhVienDiemThap as $diem) {
            $mucDo = $this->xacDinhMucDoCanhBao($diem->DiemTong, $nguongDiem);
            
            $canhBao = [
                'id' => 'diem_thap_' . $diem->MaSV . '_' . $diem->MaMH . '_' . $diem->MaLop,
                'MaSV' => $diem->MaSV,
                'MaMH' => $diem->MaMH,
                'MaLop' => $diem->MaLop,
                'LoaiCanhBao' => 'diem_thap',
                'MucDo' => $mucDo,
                'NoiDung' => "Sinh viên có điểm {$diem->DiemTong} trong môn {$diem->monHoc->TenMH}",
                'GiaTriHienTai' => $diem->DiemTong,
                'NguongCanhBao' => $nguongDiem,
                'NgayTao' => $diem->updated_at ?? $diem->created_at,
                'sinhVien' => $diem->sinhVien,
                'monHoc' => $diem->monHoc,
                'lopHoc' => $diem->lopHoc
            ];

            $canhBaoList->push($canhBao);
        }

        return $canhBaoList;
    }

    /**
     * Phát hiện sinh viên có xu hướng tụt hạng
     */
    public function phatHienTutHang($nguongTutHang = 1.0)
    {
        // Lấy điểm trung bình của sinh viên theo thời gian
        $diemTheoThoiGian = DB::table('diemthi as dt')
            ->join('sinhvien as sv', 'dt.MaSV', '=', 'sv.MaSV')
            ->join('lophoc as lh', 'dt.MaLop', '=', 'lh.MaLop')
            ->join('monhoc as mh', 'dt.MaMH', '=', 'mh.MaMH')
            ->select(
                'dt.MaSV',
                'dt.MaLop',
                'dt.MaMH',
                'dt.DiemTong',
                'dt.updated_at',
                'sv.HoTen',
                'lh.TenLop',
                'mh.TenMH'
            )
            ->where('dt.DiemTong', '>', 0)
            ->orderBy('dt.MaSV')
            ->orderBy('dt.updated_at')
            ->get();

        $canhBaoList = collect();
        $sinhVienGroups = $diemTheoThoiGian->groupBy('MaSV');

        foreach ($sinhVienGroups as $maSV => $diemThi) {
            if ($diemThi->count() < 3) continue; // Cần ít nhất 3 điểm để phân tích xu hướng

            // Tính điểm trung bình theo từng giai đoạn
            $diemArray = $diemThi->sortBy('updated_at')->values();
            $soDiem = $diemArray->count();
            
            // Chia thành 2 giai đoạn: đầu và cuối
            $diemDau = $diemArray->take(ceil($soDiem / 2));
            $diemCuoi = $diemArray->skip(floor($soDiem / 2));
            
            $diemTBDau = $diemDau->avg('DiemTong');
            $diemTBCuoi = $diemCuoi->avg('DiemTong');
            
            $doChenhLech = $diemTBDau - $diemTBCuoi;
            
            if ($doChenhLech >= $nguongTutHang) {
                $mucDo = $this->xacDinhMucDoCanhBao($doChenhLech, $nguongTutHang);
                
                $canhBao = [
                    'id' => 'tut_hang_' . $maSV . '_' . $diemArray->first()->MaLop,
                    'MaSV' => $maSV,
                    'MaMH' => null,
                    'MaLop' => $diemArray->first()->MaLop,
                    'LoaiCanhBao' => 'tut_hang',
                    'MucDo' => $mucDo,
                    'NoiDung' => "Sinh viên có xu hướng tụt hạng: Điểm TB đầu {$diemTBDau}, cuối {$diemTBCuoi} (tụt {$doChenhLech})",
                    'GiaTriHienTai' => $diemTBCuoi,
                    'NguongCanhBao' => $nguongTutHang,
                    'NgayTao' => now(),
                    'sinhVien' => sinhvien::find($maSV),
                    'monHoc' => null,
                    'lopHoc' => lophoc::find($diemArray->first()->MaLop)
                ];

                $canhBaoList->push($canhBao);
            }
        }

        return $canhBaoList;
    }

    /**
     * Chạy tất cả các loại cảnh báo
     */
    public function chayTatCaCanhBao()
    {
        $tatCaCanhBao = collect();
        
        // Phát hiện điểm thấp
        $tatCaCanhBao = $tatCaCanhBao->merge($this->phatHienDiemThap());
        
        // Phát hiện tụt hạng
        $tatCaCanhBao = $tatCaCanhBao->merge($this->phatHienTutHang());

        $canhBaoCao = $tatCaCanhBao->where('MucDo', 'cao')->count();
        $canhBaoTrungBinh = $tatCaCanhBao->where('MucDo', 'trung_binh')->count();
        $canhBaoThap = $tatCaCanhBao->where('MucDo', 'thap')->count();

        return [
            'tong_canh_bao' => $tatCaCanhBao->count(),
            'canh_bao_cao' => $canhBaoCao,
            'canh_bao_trung_binh' => $canhBaoTrungBinh,
            'canh_bao_thap' => $canhBaoThap,
            'danh_sach_canh_bao' => $tatCaCanhBao
        ];
    }

    /**
     * Lấy danh sách cảnh báo với bộ lọc
     */
    public function layDanhSachCanhBao($filters = [])
    {
        $ketQua = $this->chayTatCaCanhBao();
        $danhSachCanhBao = $ketQua['danh_sach_canh_bao'];

        // Áp dụng bộ lọc
        if (!empty($filters['muc_do'])) {
            $danhSachCanhBao = $danhSachCanhBao->where('MucDo', $filters['muc_do']);
        }

        if (!empty($filters['loai_canh_bao'])) {
            $danhSachCanhBao = $danhSachCanhBao->where('LoaiCanhBao', $filters['loai_canh_bao']);
        }

        if (!empty($filters['ma_lop'])) {
            $danhSachCanhBao = $danhSachCanhBao->where('MaLop', $filters['ma_lop']);
        }

        return $danhSachCanhBao->values();
    }

    /**
     * Thống kê cảnh báo
     */
    public function thongKeCanhBao()
    {
        $ketQua = $this->chayTatCaCanhBao();
        $danhSachCanhBao = $ketQua['danh_sach_canh_bao'];

        $theoLoai = $danhSachCanhBao->groupBy('LoaiCanhBao')->map(function($group) {
            return (object)['LoaiCanhBao' => $group->first()['LoaiCanhBao'], 'so_luong' => $group->count()];
        })->values();

        $theoMucDo = $danhSachCanhBao->groupBy('MucDo')->map(function($group) {
            return (object)['MucDo' => $group->first()['MucDo'], 'so_luong' => $group->count()];
        })->values();

        return [
            'tong_canh_bao' => $ketQua['tong_canh_bao'],
            'chua_xu_ly' => $ketQua['tong_canh_bao'], // Tất cả đều chưa xử lý vì không lưu DB
            'da_xu_ly' => 0,
            'muc_do_cao' => $ketQua['canh_bao_cao'],
            'muc_do_trung_binh' => $ketQua['canh_bao_trung_binh'],
            'muc_do_thap' => $ketQua['canh_bao_thap'],
            'theo_loai' => $theoLoai,
            'theo_muc_do' => $theoMucDo
        ];
    }

    /**
     * Lấy chi tiết cảnh báo theo ID
     */
    public function layChiTietCanhBao($id)
    {
        $ketQua = $this->chayTatCaCanhBao();
        return $ketQua['danh_sach_canh_bao']->firstWhere('id', $id);
    }

    /**
     * Xác định mức độ cảnh báo
     */
    private function xacDinhMucDoCanhBao($giaTri, $nguong)
    {
        $tyLe = $giaTri / $nguong;
        
        if ($tyLe <= 0.5) return 'cao';        // Dưới 50% ngưỡng
        if ($tyLe <= 0.8) return 'trung_binh';  // 50-80% ngưỡng
        return 'thap';                          // Trên 80% ngưỡng
    }
}