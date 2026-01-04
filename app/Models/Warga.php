<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    use HasFactory;

    protected $table = 'warga';
    protected $primaryKey = 'warga_id';

    protected $fillable = [
        'no_ktp',
        'nama',
        'jenis_kelamin',
        'agama',
        'pekerjaan',
        'telp',
        'email'
    ];

    // ===== RELATIONSHIPS =====
    public function persil()
    {
        return $this->hasMany(Persil::class, 'pemilik_warga_id');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id', 'warga_id')
                    ->where('ref_table', 'warga')
                    ->orderBy('sort_order');
    }

    public function avatar()
    {
        return $this->hasOne(Media::class, 'ref_id', 'warga_id')
                    ->where('ref_table', 'warga')
                    ->where('caption', 'like', '%avatar%')
                    ->latest();
    }

    // ===== SCOPES =====
    public function scopeFilter($query, $request, $filterableColumns)
    {
        foreach ($filterableColumns as $column) {
            if ($request->filled($column)) {
                $query->where($column, $request->input($column));
            }
        }
        return $query;
    }

    public function scopeSearch($query, $request, $columns)
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

    // ===== MEDIA METHODS =====
    public function uploadAvatar($file)
    {
        $fileName = 'avatar_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = 'uploads/warga/' . $this->warga_id . '/' . $fileName;

        $file->storeAs('public/uploads/warga/' . $this->warga_id, $fileName);

        return Media::create([
            'ref_table' => 'warga',
            'ref_id' => $this->warga_id,
            'file_url' => $path,
            'caption' => 'Avatar',
            'mime_type' => $file->getMimeType(),
            'sort_order' => 0
        ]);
    }
}
