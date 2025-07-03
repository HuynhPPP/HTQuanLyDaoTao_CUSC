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
        Schema::create('bao_cao_do_an_upload', function (Blueprint $table) {
            $table->id();
    $table->string('ten_file');
    $table->string('duong_dan');
    $table->string('lop');
    $table->string('dot_bao_cao')->nullable();
    $table->string('nguoi_lap')->nullable();
    $table->date('ngay_lap')->nullable();
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bao_cao_do_an_upload');
    }
};
