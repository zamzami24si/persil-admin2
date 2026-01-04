<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPenggunaanSeeder extends Seeder
{
    public function run(): void
    {
        // Hanya buat 10 data dasar saja, tidak perlu 100
        $jenisDasar = [
            ['Perumahan', 'Tanah untuk tempat tinggal'],
            ['Pertanian', 'Tanah untuk kegiatan pertanian'],
            ['Perkebunan', 'Tanah untuk perkebunan'],
            ['Peternakan', 'Tanah untuk peternakan'],
            ['Industri', 'Tanah untuk kegiatan industri'],
            ['Komersial', 'Tanah untuk kegiatan perdagangan'],
            ['Perkantoran', 'Tanah untuk perkantoran'],
            ['Fasilitas Umum', 'Tanah untuk fasilitas umum'],
            ['Rekreasi', 'Tanah untuk rekreasi'],
            ['Konservasi', 'Tanah untuk konservasi alam'],
        ];

        $data = [];
        foreach ($jenisDasar as $jenis) {
            $data[] = [
                'nama_penggunaan' => $jenis[0], // TIDAK perlu tambahan angka
                'keterangan' => $jenis[1],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('jenis_penggunaan')->insert($data);
    }
}
