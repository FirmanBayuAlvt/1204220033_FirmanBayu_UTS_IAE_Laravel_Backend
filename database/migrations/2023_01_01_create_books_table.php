<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('pengarang');
            $table->string('penerbit');
            $table->integer('tahun_terbit');
            $table->string('kategori');
            $table->enum('status', ['Tersedia', 'Dipinjam'])->default('Tersedia');
            $table->string('isbn')->nullable();
            $table->integer('jumlah_halaman')->nullable();
            $table->text('deskripsi')->nullable(); // ✅ PASTIKAN ADA
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
