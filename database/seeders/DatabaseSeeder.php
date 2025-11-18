<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus semua data lama
        DB::table('books')->truncate();

        $books = [
            [
                'judul' => 'Laskar Pelangi',
                'pengarang' => 'Andrea Hirata',
                'penerbit' => 'Bentang Pustaka',
                'tahun_terbit' => 2005,
                'kategori' => 'Fiksi',
                'status' => 'Tersedia',
                'isbn' => '979-3062-79-7',
                'jumlah_halaman' => 529,
                'deskripsi' => 'Novel tentang perjuangan sekelompok anak miskin di Belitung.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Bumi Manusia',
                'pengarang' => 'Pramoedya Ananta Toer',
                'penerbit' => 'Hasta Mitra',
                'tahun_terbit' => 1980,
                'kategori' => 'Fiksi',
                'status' => 'Dipinjam',
                'isbn' => '979-9731-23-X',
                'jumlah_halaman' => 535,
                'deskripsi' => 'Novel sejarah tentang kebangkitan nasional Indonesia.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'judul' => 'Pemrograman PHP untuk Pemula',
                'pengarang' => 'Budi Raharjo',
                'penerbit' => 'Informatika',
                'tahun_terbit' => 2023,
                'kategori' => 'Teknologi',
                'status' => 'Tersedia',
                'isbn' => '978-623-123-456-1',
                'jumlah_halaman' => 300,
                'deskripsi' => 'Panduan lengkap belajar PHP dari dasar.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        DB::table('books')->insert($books);

        $this->command->info('Successfully seeded ' . count($books) . ' books!');
    }
}
