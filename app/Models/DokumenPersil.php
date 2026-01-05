<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DokumenPersil extends Model
{
    use HasFactory;

    protected $table = 'dokumen_persil';
    protected $primaryKey = 'dokumen_id';

    protected $fillable = [
        'persil_id',
        'jenis_dokumen',
        'nomor',
        'keterangan'
    ];

    public function persil()
    {
        return $this->belongsTo(Persil::class, 'persil_id', 'persil_id');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id', 'dokumen_id')
                    ->where('ref_table', 'dokumen_persil')
                    ->orderBy('sort_order');
    }

    // Method Penting untuk Upload
    public function uploadDokumenFile($file, $caption = 'Dokumen')
    {
        $path = $file->store('uploads/dokumen_persil', 'public');

        return Media::create([
            'ref_table' => 'dokumen_persil',
            'ref_id'    => $this->dokumen_id,
            'file_url'  => $path,
            'caption'   => $caption,
            'mime_type' => $file->getClientMimeType(),
            'sort_order'=> 0
        ]);
    }
}
