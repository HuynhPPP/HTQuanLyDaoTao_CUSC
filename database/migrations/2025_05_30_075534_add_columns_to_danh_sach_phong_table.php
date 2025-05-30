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
        Schema::table('DanhSachPhong', function (Blueprint $table) {
            $table->date('NgaySuDung')->nullable();
            $table->string('Ca', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('DanhSachPhong', function (Blueprint $table) {
            $table->dropColumn(['NgaySuDung', 'Ca']);
        });
    }
};
