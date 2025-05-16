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
        Schema::create('giaovien', function (Blueprint $table) {
            $table->string('MaGV', 12)->primary();
            $table->string('HoTenGV', 50);
            $table->boolean('GioiTinh')->nullable(); // 1: Nam, 0: Nữ
            $table->string('Email', 50)->unique();
            $table->string('Sdt', 15)->nullable();
            
            // Khóa ngoại từ các bảng khác
            $table->string('MaHV', 12)->nullable(); // Học vị
            $table->string('TenChucVu', 30)->nullable(); // Chức vụ
            $table->string('MaDV', 12)->nullable(); // Đơn vị
            $table->string('MaBang', 12)->nullable(); // Bằng cấp
            
            // Thông tin về loại giáo viên
            $table->enum('LoaiGV', ['CoHuu', 'MoiGiang'])->default('CoHuu');
            
            // Thông tin chuyên môn
            $table->string('ChuyenNganh', 100)->nullable();
            $table->text('GhiChu')->nullable();
            
            // Thời gian công tác
            $table->date('NgayBatDauCongTac')->nullable();
            $table->date('NgayKetThucCongTac')->nullable();

            $table->timestamps();

            // Khóa ngoại
            $table->foreign('MaHV')->references('MaHV')->on('hocvi')->onDelete('set null');
            $table->foreign('TenChucVu')->references('TenChucVu')->on('chucvu')->onDelete('set null');
            $table->foreign('MaDV')->references('MaDV')->on('donvi')->onDelete('set null');
            $table->foreign('MaBang')->references('MaBang')->on('bangcapcanbo')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('giaovien');
    }
};
