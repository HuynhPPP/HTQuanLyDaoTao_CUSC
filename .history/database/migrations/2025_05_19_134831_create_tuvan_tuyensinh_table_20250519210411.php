<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tuvan_tuyensinh', function (Blueprint $table) {
            $table->string('MaTuVan', 12)->primary();
            $table->string('HoTen', 50);
            $table->string('Email', 50)->nullable();
            $table->string('SoDienThoai', 15)->nullable();
            $table->text('NoiDungTuVan')->nullable();
            $table->timestamp('NgayTuVan')->useCurrent();
            $table->enum('TrangThai', ['ChuaLienHe', 'DaLienHe', 'DaHoanThanh'])->default('ChuaLienHe');
            $table->string('NhanVienTuVan', 12)->nullable()->collation('utf8mb4_0900_ai_ci');
            $table->foreign('NhanVienTuVan')->references('MaCB')->on('canbo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tuvan_tuyensinh');
    }
};
