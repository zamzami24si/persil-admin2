<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        $persilIds = DB::table('persil')->pluck('persil_id')->toArray();
        $dokumenIds = DB::table('dokumen_persil')->pluck('dokumen_id')->toArray();
        $sengketaIds = DB::table('sengketa_persil')->pluck('sengketa_id')->toArray();

        $data = [];

        $refTables = [
            'persil' => $persilIds,
            'dokumen_persil' => $dokumenIds,
            'sengketa_persil' => $sengketaIds
        ];

        $mimeTypes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/bmp',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/zip',
            'application/vnd.rar'
        ];

        $captions = [
            'Foto Bidang Tanah Depan', 'Foto Bidang Tanah Belakang',
            'Foto Batas Timur', 'Foto Batas Barat', 'Foto Batas Utara',
            'Foto Batas Selatan', 'Scan Sertifikat', 'Scan AJB',
            'Scan Girik', 'Scan PBB', 'Peta Digital', 'Peta Manual',
            'Scan Surat Tanah', 'Foto Dokumentasi', 'Bukti Pembayaran',
            'Surat Keterangan', 'Laporan Survey', 'Denah Lokasi',
            'Foto Sengketa 1', 'Foto Sengketa 2', 'Dokumen Bukti',
            'Surat Gugatan', 'Putusan Pengadilan', 'Berita Acara'
        ];

        for ($i = 1; $i <= 100; $i++) {
            $refTable = array_rand($refTables);
            $refId = $refTables[$refTable][array_rand($refTables[$refTable])];
            $mimeType = $mimeTypes[array_rand($mimeTypes)];
            $caption = $captions[array_rand($captions)];

            $extension = $this->getExtensionFromMimeType($mimeType);

            $data[] = [
                'ref_table' => $refTable,
                'ref_id' => $refId,
                'file_url' => "uploads/{$refTable}/{$refId}/file_{$i}.{$extension}",
                'caption' => $caption . ' - ' . $i,
                'mime_type' => $mimeType,
                'sort_order' => mt_rand(1, 20),
                'created_at' => now()->subDays(mt_rand(1, 365)),
                'updated_at' => now(),
            ];
        }

        DB::table('media')->insert($data);
    }

    private function getExtensionFromMimeType($mimeType): string
    {
        $extensions = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/bmp' => 'bmp',
            'application/pdf' => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
            'application/vnd.ms-excel' => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx',
            'application/zip' => 'zip',
            'application/vnd.rar' => 'rar',
        ];

        return $extensions[$mimeType] ?? 'jpg';
    }
}
