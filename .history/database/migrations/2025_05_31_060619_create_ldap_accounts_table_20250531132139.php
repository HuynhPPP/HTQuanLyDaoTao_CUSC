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
            $table->string('MaSV')->unique();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('full_name');
            $table->string('initial_password');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
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
