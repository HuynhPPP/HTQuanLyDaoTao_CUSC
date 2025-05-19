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
        Schema::create('tainguyen_hoctap', function (Blueprint $table) {
            $table->string('MaTaiNguyen', 12)->primary();
            $table->string('TenTaiNguyen', 255);
            $table->enum('LoaiTaiNguyen', ['Sach', 'TaiLieu', 'PhanMem', 'ThietBiThucHanh'])->nullable();
            $table->text('MoTa')->nullable();
            $table->enum('TrangThai', ['KhaDung', 'KhongKhaDung'])->default('KhaDung');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tainguyen_hoctap');
    }
};
