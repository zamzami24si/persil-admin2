<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder; // Tambahkan ini
use Illuminate\Support\Facades\Storage;

class Persil extends Model
{
    use HasFactory;

    protected $table = 'persil';
    protected $primaryKey = 'persil_id';

    protected $fillable = [
        'kode_persil',
        'pemilik_warga_id',
        'luas_m2',
        'penggunaan',
        'alamat_lahan',
        'rt',
        'rw',
    ];

    // ===== SCOPE METHODS (Pencarian & Filter) =====
    public function scopeFilter(Builder $query, $request, array $filterableColumns = [])
    {
        if ($request) {
            foreach ($filterableColumns as $column) {
                if ($request->filled($column)) {
                    $query->where($column, $request->$column);
                }
            }
        }
        return $query;
    }

    public function scopeSearch(Builder $query, $request, array $searchableColumns = [])
    {
        if ($request && $request->filled('search') && !empty($searchableColumns)) {
            $search = $request->search;
            $query->where(function ($q) use ($search, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $q->orWhere($column, 'like', '%' . $search . '%');
                }
            });
        }
        return $query;
    }

    // ===== RELATIONSHIPS =====

    public function pemilik()
    {
        return $this->belongsTo(Warga::class, 'pemilik_warga_id', 'warga_id');
    }

    public function dokumen()
    {
        return $this->hasMany(DokumenPersil::class, 'persil_id');
    }

    public function peta()
    {
        return $this->hasMany(PetaPersil::class, 'persil_id');
    }

    // [PENTING] Method ini yang sebelumnya hilang
    public function sengketa()
    {
        return $this->hasMany(SengketaPersil::class, 'persil_id');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id', 'persil_id')
                    ->where('ref_table', 'persil')
                    ->orderBy('sort_order');
    }

    // ===== UPLOAD METHODS (Agar Upload Foto Tetap Jalan) =====

    public function uploadFotoBidang($file, $caption = 'Foto Bidang')
    {
        $path = $file->store('uploads/persil/foto', 'public');

        return Media::create([
            'ref_table' => 'persil',
            'ref_id'    => $this->persil_id,
            'file_url'  => $path,
            'caption'   => $caption,
            'mime_type' => $file->getClientMimeType(),
            'sort_order'=> 0
        ]);
    }

    public function uploadKoordinatFile($file, $caption = 'File Koordinat')
    {
        $path = $file->store('uploads/persil/dokumen', 'public');

        return Media::create([
            'ref_table' => 'persil',
            'ref_id'    => $this->persil_id,
            'file_url'  => $path,
            'caption'   => $caption,
            'mime_type' => $file->getClientMimeType(),
            'sort_order'=> 1
        ]);
    }
}
