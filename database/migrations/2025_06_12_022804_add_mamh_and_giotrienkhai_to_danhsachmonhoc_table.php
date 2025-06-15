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
        Schema::table('DanhSachMH', function (Blueprint $table) {
            $table->string('MaMH')->nullable()->after('MaHK');
            $table->string('GioTrienKhai')->nullable()->after('TenMH');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('DanhSachMH', function (Blueprint $table) {
            $table->dropColumn('MaMH');
            $table->dropColumn('GioTrienKhai');
        });
    }
};
