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
        Schema::create('thong_ke_bao_cao_do_ans', function (Blueprint $table) {
            $table->id();

            // Khai báo cột đúng collation trước
            $table->string('class_id', 12)->collation('utf8mb4_0900_ai_ci');
            $table->string('instructor_id', 12)->nullable()->collation('utf8mb4_0900_ai_ci');
            $table->string('reviewer_id', 12)->nullable()->collation('utf8mb4_0900_ai_ci');

            // Đặt foreign key sau khi đã định nghĩa đúng collation
            $table->foreign('class_id')
                  ->references('MaLop')
                  ->on('lophoc')
                  ->onDelete('cascade');

            $table->foreign('instructor_id')
                  ->references('MaGV')
                  ->on('giaovien')
                  ->onDelete('set null');

            $table->foreign('reviewer_id')
                  ->references('MaGV')
                  ->on('giaovien')
                  ->onDelete('set null');

            $table->date('report_date');
            $table->time('report_time_start');
            $table->time('report_time_end');
            $table->string('location');
            $table->string('report_name');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_ke_bao_cao_do_ans');
    }
};
