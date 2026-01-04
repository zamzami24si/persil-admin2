<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SengketaPersilSeeder extends Seeder
{
    public function run(): void
    {
        $persilIds = DB::table('persil')->pluck('persil_id')->toArray();
        $wargaNama = DB::table('warga')->pluck('nama')->toArray();

        $data = [];

        // SESUAIKAN dengan enum di migrasi: ['pending', 'proses', 'selesai']
        $statusList = ['pending', 'proses', 'selesai']; // HAPUS 'dibatalkan'

        for ($i = 1; $i <= 30; $i++) { // Kurangi jadi 30 data saja
            $persilId = $persilIds[array_rand($persilIds)];
            $warga1 = $wargaNama[array_rand($wargaNama)];
            $warga2 = $wargaNama[array_rand($wargaNama)];

            // Pastikan warga1 dan warga2 berbeda
            while ($warga1 === $warga2) {
                $warga2 = $wargaNama[array_rand($wargaNama)];
            }

            $status = $statusList[array_rand($statusList)];

            $data[] = [
                'persil_id' => $persilId,
                'pihak_1' => $warga1,
                'pihak_2' => $warga2,
                'kronologi' => $this->generateKronologi(),
                'status' => $status, // HANYA 'pending', 'proses', 'selesai'
                'penyelesaian' => $status === 'selesai' ? $this->generatePenyelesaian() : null,
                'created_at' => now()->subDays(mt_rand(1, 730)),
                'updated_at' => now(),
            ];
        }

        DB::table('sengketa_persil')->insert($data);
    }

    private function generateKronologi(): string
    {
        $kronologi = [
            'Sengketa batas tanah antara kedua pihak. Kedua pihak mengklaim bagian tanah yang sama.',
            'Perselisihan hak milik tanah warisan. Masing-masing pihak mengaku sebagai ahli waris yang sah.',
            'Konflik penggunaan tanah. Satu pihak menggunakannya untuk pertanian, pihak lain untuk peternakan.',
            'Sengketa ganti rugi pembebasan lahan. Tidak ada kesepakatan tentang nilai ganti rugi.',
            'Klaim ganti rugi kerusakan tanaman. Tanaman rusak akibat aktivitas pihak lain.',
            'Perselisihan hak akses jalan. Satu pihak menutup akses jalan menuju tanah pihak lain.',
            'Sengketa sewa menyewa tanah. Terjadi perselisihan tentang jangka waktu dan biaya sewa.',
            'Konflik hak guna bangunan. Kedua pihak mengaku memiliki hak untuk membangun di atas tanah.',
            'Sengketa warisan tidak tertulis. Tidak ada surat wasiat yang jelas.',
            'Perselisihan dengan tetangga tentang batas pagar.'
        ];

        return $kronologi[array_rand($kronologi)];
    }

    private function generatePenyelesaian(): string
    {
        $penyelesaian = [
            'Diselesaikan melalui musyawarah desa dengan kesepakatan bersama.',
            'Dibawa ke pengadilan dan diputuskan oleh hakim.',
            'Diselesaikan oleh mediator dari kantor pertanahan.',
            'Kedua pihak sepakat untuk berdamai dengan pembagian tanah.',
            'Diselesaikan dengan ganti rugi yang disepakati bersama.',
            'Kedua pihak sepakat untuk menjual tanah dan membagi hasilnya.',
            'Diselesaikan melalui RT/RW setempat.',
            'Dibawa ke Badan Penyelesaian Sengketa Pertanahan.',
            'Kedua pihak sepakat untuk menyewakan tanah dan membagi hasil sewa.',
            'Diselesaikan dengan pemetaan ulang batas tanah oleh surveyor.'
        ];

        return $penyelesaian[array_rand($penyelesaian)];
    }
}
