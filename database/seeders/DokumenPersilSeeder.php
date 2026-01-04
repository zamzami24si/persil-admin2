<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DokumenPersilSeeder extends Seeder
{
    public function run(): void
    {
        $persilIds = DB::table('persil')->pluck('persil_id')->toArray();

        $data = [];

        $jenisDokumen = [
            'Sertifikat Hak Milik (SHM)', 'Sertifikat Hak Guna Bangunan (SHGB)',
            'Sertifikat Hak Pakai (SHP)', 'Girik', 'Petok D', 'Letter C',
            'Surat Keterangan Tanah', 'PPAT', 'AJB (Akta Jual Beli)', 'SKPT',
            'Izin Lokasi', 'Izin Mendirikan Bangunan (IMB)', 'PBB (Pajak Bumi dan Bangunan)',
            'Surat Kematian', 'Surat Wasiat', 'Akta Hibah',
            'Surat Tukar Menukar', 'Surat Gadai', 'Surat Sewa Menyewa',
            'SK Penggunaan Tanah'
        ];

        $keteranganList = [
            'Dokumen asli disimpan di kantor desa',
            'Dokumen fotokopi yang dilegalisir',
            'Dokumen masih dalam proses verifikasi',
            'Dokumen telah diverifikasi dan valid',
            'Dokumen perlu perpanjangan',
            'Dokumen berlaku hingga 5 tahun ke depan',
            'Dokumen diterbitkan oleh BPN',
            'Dokumen diterbitkan oleh Kantor Pertanahan',
            'Dokumen diterbitkan oleh Kelurahan',
            'Dokumen diterbitkan oleh Kecamatan'
        ];

        for ($i = 1; $i <= 100; $i++) {
            $persilId = $persilIds[array_rand($persilIds)];
            $jenis = $jenisDokumen[array_rand($jenisDokumen)];

            $data[] = [
                'persil_id' => $persilId,
                'jenis_dokumen' => $jenis,
                'nomor' => $this->generateNomorDokumen($jenis, $i),
                'keterangan' => $keteranganList[array_rand($keteranganList)],
                'created_at' => now()->subDays(mt_rand(1, 365)),
                'updated_at' => now(),
            ];
        }

        DB::table('dokumen_persil')->insert($data);
    }

    private function generateNomorDokumen($jenis, $index): string
    {
        $prefix = '';

        if (str_contains($jenis, 'SHM')) {
            $prefix = 'SHM';
        } elseif (str_contains($jenis, 'SHGB')) {
            $prefix = 'SHGB';
        } elseif (str_contains($jenis, 'SHP')) {
            $prefix = 'SHP';
        } elseif (str_contains($jenis, 'Girik')) {
            $prefix = 'GRK';
        } elseif (str_contains($jenis, 'Petok')) {
            $prefix = 'PTK';
        } elseif (str_contains($jenis, 'Letter')) {
            $prefix = 'LTR-C';
        } else {
            $prefix = 'DOC';
        }

        return $prefix . '/' . str_pad($index, 4, '0', STR_PAD_LEFT) . '/' . mt_rand(2020, 2024);
    }
}
