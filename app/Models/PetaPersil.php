<?php
// app/Models/PetaPersil.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PetaPersil extends Model
{
    protected $table = 'peta_persil';
    protected $primaryKey = 'peta_id';

    protected $fillable = [
        'persil_id',
        'geojson',
        'panjang_m',
        'lebar_m',
        // 'luas_dari_dimensi',  <-- HAPUS INI DARI FILLABLE
    ];

    protected $casts = [
        'geojson' => 'array',
        'panjang_m' => 'decimal:2',
        'lebar_m' => 'decimal:2',
        // 'luas_dari_dimensi' => 'decimal:2', <-- HAPUS INI JUGA
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ===== ACCESSOR BARU (PENTING) =====
    // Ini fungsinya menghitung luas otomatis saat dipanggil $peta->luas_dari_dimensi
    public function getLuasDariDimensiAttribute()
    {
        if ($this->panjang_m && $this->lebar_m) {
            return $this->panjang_m * $this->lebar_m;
        }
        return 0;
    }

    // ... (Sisa kode ke bawah biarkan sama seperti sebelumnya) ...

    public function getGeojsonAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true);
        }
        return $value;
    }

    public function setGeojsonAttribute($value)
    {
        if (is_array($value) || is_object($value)) {
            $this->attributes['geojson'] = json_encode($value);
        } else {
            $this->attributes['geojson'] = $value;
        }
    }

    public function persil()
    {
        return $this->belongsTo(Persil::class, 'persil_id', 'persil_id');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id', 'peta_id')
                    ->where('ref_table', 'peta_persil')
                    ->orderBy('sort_order');
    }

    public function uploadPetaFile($file, $caption = null)
    {
        // Simpan ke folder uploads/peta_persil
        $path = $file->store('uploads/peta_persil', 'public');

        return Media::create([
            'ref_table' => 'peta_persil',
            'ref_id'    => $this->peta_id,
            'file_url'  => $path,
            'caption'   => $caption ?: 'Peta ' . $this->persil->kode_persil,
            'mime_type' => $file->getClientMimeType(),
            'sort_order'=> 0
        ]);
    }

    public function scopeSearch($query, $request, $columns = [])
    {
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search, $columns) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'like', "%{$search}%");
                }

                $q->orWhereHas('persil', function ($q2) use ($search) {
                    $q2->where('kode_persil', 'like', "%{$search}%")
                       ->orWhereHas('pemilik', function ($q3) use ($search) {
                           $q3->where('nama', 'like', "%{$search}%");
                       });
                });
            });
        }
        return $query;
    }
}
