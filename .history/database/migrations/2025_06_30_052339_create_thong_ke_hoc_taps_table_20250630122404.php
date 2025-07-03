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
        Schema::create('thong_ke_hoc_tap', function (Blueprint $table) {
            $table->id();
            $table->string('ma_chuong_trinh');
            $table->string('hoc_ki');
            $table->integer('tong_sinh_vien')->default(0);
            $table->integer('sinh_vien_gioi')->default(0);
            $table->integer('sinh_vien_kha')->default(0);
            $table->integer('sinh_vien_trung_binh')->default(0);
            $table->integer('sinh_vien_yeu')->default(0);
            $table->float('diem_trung_binh_tong_khoa')->default(0);
            $table->float('ty_le_tot_nghiep')->default(0);
            $table->timestamps();

            $table->foreign('ma_chuong_trinh')
                  ->references('MaChuongTrinh')
                  ->on('chuongtrinh')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_ke_hoc_tap');
    }
};
