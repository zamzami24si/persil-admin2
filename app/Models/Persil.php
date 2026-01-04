<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    // ===== SCOPE METHODS =====

    /**
     * Scope untuk filter data
     */
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

    /**
     * Scope untuk pencarian data
     */
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
        return $this->belongsTo(Warga::class, 'pemilik_warga_id');
    }

    public function dokumen()
    {
        return $this->hasMany(DokumenPersil::class, 'persil_id');
    }

    public function peta()
    {
        return $this->hasMany(PetaPersil::class, 'persil_id');
    }

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

    public function fotoBidang()
    {
        return $this->hasMany(Media::class, 'ref_id', 'persil_id')
                    ->where('ref_table', 'persil')
                    ->where('caption', 'like', '%foto_bidang%')
                    ->orderBy('sort_order');
    }

    public function koordinatFiles()
    {
        return $this->hasMany(Media::class, 'ref_id', 'persil_id')
                    ->where('ref_table', 'persil')
                    ->where('caption', 'like', '%koordinat%')
                    ->orderBy('sort_order');
    }

    public function penggunaanRel()
    {
        return $this->belongsTo(JenisPenggunaan::class, 'penggunaan', 'nama_penggunaan');
    }

    // ===== MEDIA METHODS =====
    public function uploadFotoBidang($file, $caption = null)
    {
        return $this->uploadMedia($file, 'foto_bidang', $caption);
    }

    public function uploadKoordinatFile($file, $caption = null)
    {
        return $this->uploadMedia($file, 'koordinat', $caption);
    }

    private function uploadMedia($file, $type, $caption = null)
    {
        // Buat direktori jika belum ada
        $directory = 'uploads/persil/' . $this->persil_id;
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }

        $fileName = $type . '_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $directory . '/' . $fileName;

        $file->storeAs('public/' . $directory, $fileName);

        return Media::create([
            'ref_table' => 'persil',
            'ref_id' => $this->persil_id,
            'file_url' => $path,
            'caption' => $caption ?? ucfirst(str_replace('_', ' ', $type)),
            'mime_type' => $file->getMimeType(),
            'sort_order' => $this->media()->count()
        ]);
    }
}
