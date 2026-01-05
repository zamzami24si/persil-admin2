<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage; // Pastikan import ini

class SengketaPersil extends Model
{
    use HasFactory;

    protected $table = 'sengketa_persil';
    protected $primaryKey = 'sengketa_id';

    protected $fillable = [
        'persil_id', 'pihak_1', 'pihak_2', 'kronologi', 'status', 'penyelesaian'
    ];

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

    // ===== HELPERS =====
    public function getStatusLabelAttribute()
    {
        $statuses = ['proses' => 'Proses', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'];
        return $statuses[$this->status] ?? $this->status;
    }

    public function getStatusBadgeClassAttribute()
    {
        $classes = ['proses' => 'bg-warning', 'selesai' => 'bg-success', 'dibatalkan' => 'bg-danger'];
        return $classes[$this->status] ?? 'bg-secondary';
    }

    // ===== MEDIA UPLOAD =====
    public function uploadBuktiSengketa($file, $caption = 'Bukti Sengketa')
    {
        $path = $file->store('uploads/sengketa_persil', 'public');

        return Media::create([
            'ref_table' => 'sengketa_persil',
            'ref_id'    => $this->sengketa_id,
            'file_url'  => $path,
            'caption'   => $caption,
            'mime_type' => $file->getClientMimeType(),
            'sort_order'=> 0
        ]);
    }
}
