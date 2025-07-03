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
        Schema::create('bao_cao_do_an_upload', function (Blueprint $table) {
            $table->id();
            $table->string('ten_file');
            $table->string('duong_dan');
            $table->string('lop_bao_cao')->comment('Tên lớp từ file word (e.g., CP24Y0G05)');
            $table->string('lan_bao_cao')->nullable()->comment('Lần báo cáo (e.g., 01)');
            $table->string('hoc_ky')->nullable()->comment('Học kỳ (e.g., 1 (Print Portfolio))');
            $table->string('giang_vien_huong_dan')->nullable()->comment('Họ tên Giáo viên hướng dẫn');
            $table->string('giang_vien_phan_bien')->nullable()->comment('Họ tên Giáo viên phản biện');
            $table->date('ngay_bao_cao')->nullable()->comment('Ngày báo cáo (e.g., 17/01/2025)');
            $table->time('gio_bao_cao_bat_dau')->nullable()->comment('Giờ báo cáo bắt đầu (e.g., 7:00)');
            $table->time('gio_bao_cao_ket_thuc')->nullable()->comment('Giờ báo cáo kết thúc (e.g., 12:00)');
            $table->string('dia_diem_bao_cao')->nullable()->comment('Địa điểm báo cáo (e.g., Lý thuyết 04)');
            $table->integer('so_luong_nhom')->nullable()->comment('Số lượng nhóm (e.g., 05 Nhóm)');
            $table->string('nguoi_lap')->nullable()->comment('Người lập bảng phân công (e.g., Hà Thanh)');
            $table->string('truong_bp_dao_tao')->nullable()->comment('P. TRƯỞNG BP ĐÀO TẠO (e.g., Cù Vĩnh Lộc)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bao_cao_do_an_upload');
    }
};
