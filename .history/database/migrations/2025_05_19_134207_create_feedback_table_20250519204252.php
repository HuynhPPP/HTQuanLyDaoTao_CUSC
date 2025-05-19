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
        Schema::create('feedback', function (Blueprint $table) {
            $table->string('MaFeedback', 12)->primary();
            $table->string('MaSV', 12)->nullable();
            $table->string('MaGV', 12)->nullable();
            $table->text('NoiDung')->nullable();
            $table->timestamp('NgayTao')->useCurrent();
            $table->enum('TrangThai', ['DaXuLy', 'ChuaXuLy'])->default('ChuaXuLy');
            $table->foreign('MaSV')->references('MaSV')->on('sinhvien');
            $table->foreign('MaGV')->references('MaGV')->on('giaovien');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
