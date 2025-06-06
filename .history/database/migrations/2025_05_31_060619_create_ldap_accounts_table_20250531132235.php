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
        Schema::create('ldap_accounts', function (Blueprint $table) {
            $table->id();
            
            // Khóa ngoại liên kết đến bảng sinh viên
            $table->string('MaSV');
            $table->foreign('MaSV')
                  ->references('MaSV')
                  ->on('SinhVien')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('full_name');
            $table->string('initial_password');
            
            // Trạng thái tài khoản
            $table->boolean('is_sent')->default(false);
            $table->boolean('is_active')->default(true);
            
            // Thêm index để tăng hiệu suất truy vấn
            $table->index('MaSV');
            $table->index('username');
            $table->index('email');

            $table->timestamps();
        });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ldap_accounts');
    }
};
