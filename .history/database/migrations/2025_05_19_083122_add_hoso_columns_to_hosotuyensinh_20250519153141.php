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
        Schema::table('hosotuyensinh', function (Blueprint $table) {
            $table->boolean('Hinh3X4')->nullable()->after('TrangThaiHS');
            $table->boolean('HinhCCCD')->nullable()->after('Hinh3X4');
            $table->boolean('ToDangKi')->nullable()->after('HinhCCCD');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hosotuyensinh', function (Blueprint $table) {
            $table->dropColumn(['Hinh3X4', 'HinhCCCD', 'ToDangKi']);
        });
    }
};
