<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WargaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [];

        $agamaList = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
        $pekerjaanList = [
            'Wiraswasta', 'PNS', 'Guru', 'Dokter', 'Perawat', 'Programmer',
            'Desainer', 'Akuntan', 'Karyawan Swasta', 'Pedagang', 'Petani',
            'Nelayan', 'Pensiunan', 'Mahasiswa', 'Ibu Rumah Tangga',
            'Buruh', 'Supir', 'Tukang', 'Wartawan', 'Pengusaha'
        ];

        $namaDepanPria = ['Ahmad', 'Budi', 'Eko', 'Fajar', 'Hadi', 'Indra', 'Joko',
                         'Kusuma', 'Lukman', 'Muhammad', 'Nur', 'Oki', 'Putra', 'Rudi',
                         'Soleh', 'Tono', 'Umar', 'Wahyu', 'Yudi', 'Zainal'];

        $namaDepanWanita = ['Citra', 'Dewi', 'Gita', 'Hani', 'Indah', 'Kartika', 'Lina',
                           'Maya', 'Nina', 'Putri', 'Rina', 'Sari', 'Tika', 'Wati',
                           'Yuni', 'Zahra', 'Ani', 'Bunga', 'Cinta', 'Diana'];

        $namaBelakang = ['Santoso', 'Wijaya', 'Kusuma', 'Pratama', 'Nugroho', 'Putra',
                        'Hadi', 'Kurniawan', 'Siregar', 'Halim', 'Wibowo', 'Gunawan',
                        'Ramadan', 'Saputra', 'Hakim', 'Firmansyah', 'Maulana', 'Rahman',
                        'Hidayat', 'Purnomo'];

        for ($i = 1; $i <= 100; $i++) {
            $jenisKelamin = $i % 2 == 0 ? 'P' : 'L';

            if ($jenisKelamin === 'L') {
                $nama = $namaDepanPria[array_rand($namaDepanPria)] . ' ' . $namaBelakang[array_rand($namaBelakang)];
            } else {
                $nama = $namaDepanWanita[array_rand($namaDepanWanita)] . ' ' . $namaBelakang[array_rand($namaBelakang)];
            }

            $data[] = [
                'no_ktp' => $this->generateKTP(),
                'nama' => $nama,
                'jenis_kelamin' => $jenisKelamin,
                'agama' => $agamaList[array_rand($agamaList)],
                'pekerjaan' => $pekerjaanList[array_rand($pekerjaanList)],
                'telp' => $this->generateTelp(),
                'email' => $this->generateEmail($nama, $i),
                'created_at' => now()->subDays(mt_rand(1, 365)),
                'updated_at' => now(),
            ];
        }

        DB::table('warga')->insert($data);
    }

    private function generateKTP(): string
    {
        return sprintf('%016d', mt_rand(1000000000000000, 9999999999999999));
    }

    private function generateTelp(): string
    {
        $prefix = ['0812', '0813', '0814', '0815', '0816', '0817', '0818', '0819',
                  '0852', '0853', '0855', '0856', '0857', '0858', '0877', '0878'];
        return $prefix[array_rand($prefix)] . sprintf('%08d', mt_rand(10000000, 99999999));
    }

    private function generateEmail($nama, $index): string
    {
        $domain = ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com', 'bindes.id'];
        $namaEmail = strtolower(str_replace(' ', '.', $nama));
        return $namaEmail . '.' . $index . '@' . $domain[array_rand($domain)];
    }
}
