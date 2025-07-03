<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('instructor_id')->nullable()->constrained('teachers')->onDelete('set null');
            $table->foreignId('reviewer_id')->nullable()->constrained('teachers')->onDelete('set null');
            $table->date('report_date');
            $table->time('report_time_start');
            $table->time('report_time_end');
            $table->string('location');
            $table->string('report_name');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};