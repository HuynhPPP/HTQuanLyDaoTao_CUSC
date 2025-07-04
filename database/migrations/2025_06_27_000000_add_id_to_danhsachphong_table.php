<?php

use Illuminate\Database\Migrations\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('DanhSachPhong', function (Blueprint $table) {
            // Thêm cột id
            $table->id()->first();
            
            // Thêm unique constraint cho composite key
            $table->unique(['MaLop', 'TenPhong', 'NgaySuDung', 'Ca'], 'danhsachphong_composite_unique');
        });
    }

    public function down()
    {
        Schema::table('DanhSachPhong', function (Blueprint $table) {
            // Xóa unique constraint
            $table->dropUnique('danhsachphong_composite_unique');
            
            // Xóa cột id
            $table->dropColumn('id');
        });
    }
};