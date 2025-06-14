<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('thong_ke', function (Blueprint $table) {
            $table->id();
            $table->string('loai_thong_ke')->comment('Loại thống kê: sinh_vien, diem_so, hoc_tap');
            $table->string('ma_khoa')->nullable();
            $table->string('ma_lop')->nullable();
            $table->string('ma_mon_hoc')->nullable();
            $table->string('hoc_ky')->nullable();
            $table->integer('tong_so_luong')->default(0);
            $table->float('diem_trung_binh')->nullable();
            $table->float('ty_le_dau')->nullable();
            $table->json('chi_tiet')->nullable()->comment('Lưu trữ các chi tiết thống kê bổ sung');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('thong_ke');
    }
};
