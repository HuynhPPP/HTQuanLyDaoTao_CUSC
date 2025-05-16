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
        Schema::create('giaovien', function (Blueprint $table) {
            $table->string('MaGV', 12)->collation('utf8mb4_0900_ai_ci')->primary();
            $table->string('HoTenGV', 50)->collation('utf8mb4_0900_ai_ci');
            $table->boolean('GioiTinh')->nullable();
            $table->string('Email', 50)->collation('utf8mb4_0900_ai_ci')->unique();
            $table->string('Sdt', 15)->collation('utf8mb4_0900_ai_ci')->nullable();

            // Các khóa ngoại: đảm bảo đúng kiểu và collation
            $table->string('MaHV', 12)->collation('utf8mb4_0900_ai_ci')->nullable();
            $table->string('TenChucVu', 30)->collation('utf8mb4_0900_ai_ci')->nullable();
            $table->string('MaDV', 12)->collation('utf8mb4_0900_ai_ci')->nullable();
            $table->string('MaBang', 12)->collation('utf8mb4_0900_ai_ci')->nullable();

            $table->enum('LoaiGV', ['CoHuu', 'MoiGiang'])->default('CoHuu');
            $table->string('ChuyenNganh', 100)->collation('utf8mb4_0900_ai_ci')->nullable();
            $table->text('GhiChu')->collation('utf8mb4_0900_ai_ci')->nullable();

            $table->date('NgayBatDauCongTac')->nullable();
            $table->date('NgayKetThucCongTac')->nullable();

            $table->timestamps();
        });

        // Thêm các foreign key sau khi bảng đã tồn tại
        Schema::table('giaovien', function (Blueprint $table) {
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
        Schema::table('giaovien', function (Blueprint $table) {
            $table->dropForeign(['MaHV']);
            $table->dropForeign(['TenChucVu']);
            $table->dropForeign(['MaDV']);
            $table->dropForeign(['MaBang']);
        });

        Schema::dropIfExists('giaovien');
    }
};
