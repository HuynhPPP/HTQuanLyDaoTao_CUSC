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
        Schema::create('thietbi', function (Blueprint $table) {
            $table->string('MaThietBi', 12)->primary();
            $table->string('TenThietBi', 100);
            $table->text('MoTa')->nullable();
            $table->enum('TinhTrang', ['TotNhat', 'Tot', 'TrungBinh', 'CanSuaChua', 'HongHoan'])->default('Tot');
            $table->date('NgayNhap')->nullable();
            $table->date('HanBaoHanh')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thietbi');
    }
};
