<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage; // Pastikan import ini ada

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ===== RELATIONSHIPS =====

    // Relasi untuk semua media user
    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id', 'id')
                    ->where('ref_table', 'users')
                    ->orderBy('sort_order');
    }

    // Relasi spesifik untuk Avatar (Foto Profil)
    public function avatar()
    {
        // Kita ambil 1 data media terakhir milik user ini.
        // Dihapus filter 'caption' agar lebih fleksibel (apapun yg diupload terakhir jadi avatar)
        return $this->hasOne(Media::class, 'ref_id', 'id')
                    ->where('ref_table', 'users')
                    ->orderBy('media_id', 'desc'); // Pastikan ambil yang ID-nya paling besar (terbaru)
    }

    // ===== ACCESSORS =====

    // Cara panggil di blade: {{ $user->avatar_url }}
    // Tidak perlu panggil function, cukup propertinya
    public function getAvatarUrlAttribute()
    {
        // Cek apakah relasi avatar ada datanya dan file fisiknya ada
        if ($this->avatar && $this->avatar->file_url) {
            return asset('storage/' . $this->avatar->file_url);
        }

        // Generate avatar default dari inisial nama jika tidak ada foto
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    // ===== MEDIA METHODS =====

    public function uploadAvatar($file)
    {
        // 1. Hapus avatar lama jika ada (Fisik & Database)
        if ($this->avatar) {
            // Hapus file fisik dari storage
            Storage::disk('public')->delete($this->avatar->file_url);
            // Hapus record dari database
            $this->avatar->delete();
        }

        // 2. Simpan file baru
        // store() otomatis membuat nama unik (hash) agar tidak bentrok dan menghindari cache browser
        $path = $file->store('uploads/users', 'public');

        // 3. Simpan info ke database Media
        return Media::create([
            'ref_table' => 'users',
            'ref_id'    => $this->id,
            'file_url'  => $path,
            'caption'   => 'Avatar', // Label statis
            'mime_type' => $file->getClientMimeType(), // Simpan tipe file (jpg/png)
            'sort_order'=> 0
        ]);
    }

    // Method untuk cek role
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isAdmin()
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }
}
