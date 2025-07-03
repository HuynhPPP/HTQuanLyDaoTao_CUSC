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
        Schema::create('thong_ke_bao_cao_do_an', function (Blueprint $table) {
            $table->id();
            $table->foreignId('MaLop')->constrained('lophoc')->onDelete('cascade')->collation('utf8mb4_0900_ai_ci');
            $table->foreignId('instructor_id')->nullable()->constrained('giaovien')->onDelete('set null')->collation('utf8mb4_0900_ai_ci');
            $table->foreignId('reviewer_id')->nullable()->constrained('giaovien')->onDelete('set null')->collation('utf8mb4_0900_ai_ci');
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
