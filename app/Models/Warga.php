<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    // Relasi ke semua media
    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id', 'warga_id')
                    ->where('ref_table', 'warga')
                    ->orderBy('sort_order');
    }

    // Relasi khusus Avatar (Foto Profil)
    public function avatar()
    {
        return $this->hasOne(Media::class, 'ref_id', 'warga_id')
                    ->where('ref_table', 'warga')
                    ->orderBy('media_id', 'desc'); // Ambil yang paling baru
    }

    // ===== SCOPES (PENCARIAN & FILTER) - INI YANG KEMARIN HILANG =====

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

    // ===== ACCESSOR (Untuk memanggil Foto di View) =====

    // Cara panggil: {{ $warga->foto_url }}
    public function getFotoUrlAttribute()
    {
        if ($this->avatar && $this->avatar->file_url) {
            return asset('storage/' . $this->avatar->file_url);
        }

        // Gambar default (inisial nama) jika tidak ada foto
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nama) . '&color=7F9CF5&background=EBF4FF';
    }

    // ===== MEDIA METHODS (UPLOAD) =====

    public function uploadAvatar($file)
    {
        // 1. Hapus avatar lama jika ada (Fisik & Database)
        if ($this->avatar) {
            Storage::disk('public')->delete($this->avatar->file_url);
            $this->avatar->delete();
        }

        // 2. Simpan file fisik baru
        $path = $file->store('uploads/warga', 'public');

        // 3. Simpan record ke database Media
        return Media::create([
            'ref_table' => 'warga',
            'ref_id'    => $this->warga_id,
            'file_url'  => $path,
            'caption'   => 'Avatar Warga',
            'mime_type' => $file->getClientMimeType(),
            'sort_order'=> 0
        ]);
    }
}
