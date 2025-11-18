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
        Schema::table('books', function (Blueprint $table) {
            // Cek dulu apakah kolom sudah ada, baru tambahkan
            if (!Schema::hasColumn('books', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('jumlah_halaman');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            // Hapus kolom deskripsi jika ada
            if (Schema::hasColumn('books', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
        });
    }
};
