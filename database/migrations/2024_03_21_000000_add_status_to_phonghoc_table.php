<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('phonghoc', function (Blueprint $table) {
            $table->enum('TrangThai', ['Trống', 'Đang sử dụng', 'Bảo trì'])->default('Trống');
        });
    }

    public function down(): void
    {
        Schema::table('phonghoc', function (Blueprint $table) {
            $table->dropColumn('TrangThai');
        });
    }
}; 