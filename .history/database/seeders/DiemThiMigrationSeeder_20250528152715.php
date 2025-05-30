<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiemThiMigrationSeeder extends Seeder
{
    /**
     * Chuyển đổi dữ liệu điểm từ cấu trúc cũ sang mới
     *
     * @return void
     */
    public function run()
    {
        // Lấy tất cả các bản ghi điểm hiện tại
        $diemThiCu = DB::table('diemthi')->get();

        foreach ($diemThiCu as $diemThi) {
            // Giả định điểm cũ được chia đều cho các phần
            $diemLyThuyet = $diemThi->Diem * 0.5;  // 50% điểm lý thuyết
            $diemThucHanh = $diemThi->Diem * 0.3;  // 30% điểm thực hành
            $diemDuAn = $diemThi->Diem * 0.2;      // 20% điểm dự án

            // Xác định trạng thái
            $trangThai = $diemThi->Diem >= 5.0 ? 'DatChuan' : 'ChuaDatChuan';

            // Cập nhật bản ghi với cấu trúc mới
            DB::table('diemthi')
                ->where('MaSV', $diemThi->MaSV)
                ->where('TenMH', $diemThi->TenMH)
                ->where('LanThi', $diemThi->LanThi)
                ->update([
                    'DiemLyThuyet' => $diemLyThuyet,
                    'DiemThucHanh' => $diemThucHanh,
                    'DiemDuAn' => $diemDuAn,
                    'DiemTongKet' => $diemThi->Diem,
                    'TrangThai' => $trangThai,
                    'GhiChu' => $diemThi->GhiChu
                ]);
        }
    }
} 