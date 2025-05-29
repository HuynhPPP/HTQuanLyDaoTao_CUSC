<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('diemthi', function (Blueprint $table) {
            // Kiểm tra và xóa cột cũ nếu tồn tại
            if (Schema::hasColumn('diemthi', 'Diem')) {
                $table->dropColumn('Diem');
            }

            // Thêm các cột mới cho điểm chi tiết
            $table->float('DiemLyThuyet')->nullable()->comment('Điểm thi lý thuyết trắc nghiệm (50%)');
            $table->float('DiemThucHanh')->nullable()->comment('Điểm thi thực hành (30%)');
            $table->float('DiemDuAn')->nullable()->comment('Điểm dự án (20%)');
            $table->float('DiemTongKet')->nullable()->comment('Tổng điểm sau khi tính trọng số');
            
            // Thêm cột trạng thái
            $table->enum('TrangThai', ['DatChuan', 'ChuaDatChuan'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diemthi', function (Blueprint $table) {
            // Xóa các cột mới
            $table->dropColumn([
                'DiemLyThuyet', 
                'DiemThucHanh', 
                'DiemDuAn', 
                'DiemTongKet', 
                'TrangThai'
            ]);

            // Thêm lại cột Diem cũ
            $table->float('Diem')->nullable();
        });
    }
};
