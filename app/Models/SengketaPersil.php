<?php
// app/Models/SengketaPersil.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SengketaPersil extends Model
{
    use HasFactory;

    protected $table = 'sengketa_persil';
    protected $primaryKey = 'sengketa_id';

    protected $fillable = [
        'persil_id',
        'pihak_1',
        'pihak_2',
        'kronologi',
        'status',
        'penyelesaian'
    ];

    // ===== RELATIONSHIPS =====
    public function persil()
    {
        return $this->belongsTo(Persil::class, 'persil_id');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id', 'sengketa_id')
                    ->where('ref_table', 'sengketa_persil')
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

    // ===== ACCESSORS & MUTATORS =====
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'proses' => 'Proses',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            'proses' => 'bg-warning',
            'selesai' => 'bg-success',
            'dibatalkan' => 'bg-danger'
        ];

        return $classes[$this->status] ?? 'bg-secondary';
    }

    public function getBuktiFilePathAttribute()
    {
        $media = $this->media()->first();
        return $media ? $media->file_url : null;
    }

    // ===== MEDIA METHODS =====
    public function uploadBuktiSengketa($file, $caption = null)
    {
        $fileName = 'sengketa_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = 'uploads/sengketa_persil/' . $this->sengketa_id . '/' . $fileName;

        $file->storeAs('public/uploads/sengketa_persil/' . $this->sengketa_id, $fileName);

        return Media::create([
            'ref_table' => 'sengketa_persil',
            'ref_id' => $this->sengketa_id,
            'file_url' => $path,
            'caption' => $caption ?? 'Bukti Sengketa',
            'mime_type' => $file->getMimeType(),
            'sort_order' => $this->media()->count()
        ]);
    }
}
