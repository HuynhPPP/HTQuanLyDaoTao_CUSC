<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sinhvien_duthi', function (Blueprint $table) {
            $table->id();
            $table->string('MaSV', 12);
            $table->string('MaLichThi', 12);
            $table->string('MaLop', 12);
            $table->enum('TrangThaiDuThi', ['DangKy', 'DuThi', 'VangMat', 'KhongDuThi'])->default('DangKy');
            $table->text('GhiChu')->nullable();
            $table->timestamps();
        
            // Khóa ngoại
            $table->foreign('MaSV')->references('MaSV')->on('sinhvien');
            $table->foreign('MaLichThi')->references('MaLichThi')->on('lichthi');
            $table->foreign('MaLop')->references('MaLop')->on('lophoc');
        
            // Đảm bảo mỗi sinh viên chỉ đăng ký một lần cho một lịch thi
            $table->unique(['MaSV', 'MaLichThi']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sinhvien_duthi');
    }
};
