<?php
// app/Models/PetaPersil.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PetaPersil extends Model
{
    use HasFactory;

    protected $table = 'peta_persil';
    protected $primaryKey = 'peta_id';

    protected $fillable = [
        'persil_id',
        'geojson',
        'panjang_m',
        'lebar_m'
    ];

    // ===== RELATIONSHIPS =====
    public function persil()
    {
        return $this->belongsTo(Persil::class, 'persil_id');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id', 'peta_id')
                    ->where('ref_table', 'peta_persil')
                    ->orderBy('sort_order');
    }

    // ===== SCOPES =====
    public function scopeSearch(Builder $query, $request, array $searchableColumns)
    {
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm, $searchableColumns) {
                foreach ($searchableColumns as $column) {
                    $q->orWhere($column, 'LIKE', '%' . $searchTerm . '%');
                }
            });
        }
        return $query;
    }

    // ===== ACCESSORS =====
    public function getLuasDariDimensiAttribute()
    {
        if ($this->panjang_m && $this->lebar_m) {
            return $this->panjang_m * $this->lebar_m;
        }
        return null;
    }

    // ===== MEDIA METHODS =====
    public function uploadPetaFile($file, $caption = null)
    {
        $fileName = 'peta_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = 'uploads/peta_persil/' . $this->peta_id . '/' . $fileName;

        $file->storeAs('public/uploads/peta_persil/' . $this->peta_id, $fileName);

        return Media::create([
            'ref_table' => 'peta_persil',
            'ref_id' => $this->peta_id,
            'file_url' => $path,
            'caption' => $caption ?? 'Peta/Scan',
            'mime_type' => $file->getMimeType(),
            'sort_order' => $this->media()->count()
        ]);
    }
}
