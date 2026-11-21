<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor_antrean');
            $table->string('nama_pelanggan');
            $table->integer('total');
            $table->integer('uang_dibayar');
            $table->integer('kembalian');
            $table->enum('status', ['pending', 'selesai'])->default('pending');
            $table->foreignId('kasir_id')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
