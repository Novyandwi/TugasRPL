<?php

// REPLACE file ini ke dalam project Laravel kamu di:
// database/migrations/0001_01_01_000000_create_users_table.php
// (timpa file bawaan Laravel yang sudah ada di sana)

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'kasir', 'kitchen']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
