<?php
// app/Models/JenisPenggunaan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class JenisPenggunaan extends Model
{
    use HasFactory;

    protected $primaryKey = 'jenis_id';
    protected $table = 'jenis_penggunaan';

    protected $fillable = [
        'nama_penggunaan',
        'keterangan'
    ];

    public function persil()
    {
        return $this->hasMany(Persil::class, 'penggunaan', 'nama_penggunaan');
    }

    public function scopeFilter(Builder $query, $request, array $filterableColumns): Builder
    {
        foreach ($filterableColumns as $column) {
            if ($request->filled($column)) {
                $query->where($column, 'LIKE', '%' . $request->input($column) . '%');
            }
        }
        return $query;
    }

    public function scopeSearch(Builder $query, $request, array $columns): Builder
    {
        if ($request->filled('search')) {
            $query->where(function($q) use ($request, $columns) {
                foreach ($columns as $column) {
                    $q->orWhere($column, 'LIKE', '%' . $request->search . '%');
                }
            });
        }
        return $query;
    }

    // Accessor untuk jumlah persil
    public function getJumlahPersilAttribute()
    {
        return $this->persil()->count();
    }
}
