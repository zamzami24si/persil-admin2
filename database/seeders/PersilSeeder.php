<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersilSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua warga_id
        $wargaIds = DB::table('warga')->pluck('warga_id')->toArray();

        // Daftar penggunaan dari tabel atau array statis
        $penggunaanList = ['Perumahan', 'Pertanian', 'Perkebunan', 'Industri', 'Komersial'];

        $data = [];

        $rtList = ['001', '002', '003', '004', '005'];
        $rwList = ['001', '002', '003'];

        $alamatLahan = [
            'Jl. Merdeka No. 10', 'Jl. Sudirman No. 25', 'Jl. Gatot Subroto No. 5',
            'Jl. Hayam Wuruk No. 15', 'Jl. Thamrin No. 8', 'Jl. Diponegoro No. 12',
            'Jl. A. Yani No. 20', 'Jl. Pahlawan No. 30', 'Jl. Veteran No. 7',
            'Jl. Pendidikan No. 45'
        ];

        for ($i = 1; $i <= 20; $i++) { // Buat 20 data saja dulu
            $wargaId = $wargaIds[array_rand($wargaIds)];
            $penggunaan = $penggunaanList[array_rand($penggunaanList)];
            $rt = $rtList[array_rand($rtList)];
            $rw = $rwList[array_rand($rwList)];
            $luas = mt_rand(100, 1000); // 100-1000 m²

            $data[] = [
                'kode_persil' => 'PSL-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'pemilik_warga_id' => $wargaId, // BENAR: pemilik_warga_id BUKAN peniliik_warga_id
                'luas_m2' => $luas,
                'penggunaan' => $penggunaan,
                'alamat_lahan' => $alamatLahan[array_rand($alamatLahan)],
                'rt' => $rt,
                'rw' => $rw,
                // HAPUS: 'koordinat' => ... (kolom tidak ada di migrasi)
                'created_at' => now()->subDays(mt_rand(1, 365)),
                'updated_at' => now(),
            ];
        }

        DB::table('persil')->insert($data);
    }
}
