<?php
// app/Models/DokumenPersil.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    // ===== RELATIONSHIPS =====
    public function persil()
    {
        return $this->belongsTo(Persil::class, 'persil_id');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id', 'dokumen_id')
                    ->where('ref_table', 'dokumen_persil')
                    ->orderBy('sort_order');
    }

    // ===== SCOPES =====
    public function scopeFilter(Builder $query, $request, array $filterableColumns)
    {
        foreach ($filterableColumns as $column) {
            if ($request->filled($column)) {
                $query->where($column, $request->$column);
            }
        }
        return $query;
    }

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

// ===== MEDIA METHODS =====
public function uploadDokumenFile($file, $caption = null)
{
    $fileName = 'dokumen_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
    $path = 'uploads/dokumen_persil/' . $this->dokumen_id . '/' . $fileName;

    $file->storeAs('public/uploads/dokumen_persil/' . $this->dokumen_id, $fileName);

    return Media::create([
        'ref_table' => 'dokumen_persil',
        'ref_id' => $this->dokumen_id,
        'file_url' => $path,
        'caption' => $caption ?? 'Dokumen ' . $this->jenis_dokumen,
        'mime_type' => $file->getMimeType(),
        'sort_order' => $this->media()->count()
    ]);
}
}
