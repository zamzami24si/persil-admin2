<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
    public function media()
    {
        return $this->hasMany(Media::class, 'ref_id', 'id')
                    ->where('ref_table', 'users')
                    ->orderBy('sort_order');
    }

    public function avatar()
    {
        return $this->hasOne(Media::class, 'ref_id', 'id')
                    ->where('ref_table', 'users')
                    ->where('caption', 'like', '%avatar%')
                    ->latest();
    }

    // ===== ACCESSORS =====
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar->file_url);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    // ===== MEDIA METHODS =====
    public function uploadAvatar($file)
    {
        // Hapus avatar lama jika ada
        if ($this->avatar) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($this->avatar->file_url);
            $this->avatar->delete();
        }

        $fileName = 'avatar_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = 'uploads/users/' . $this->id . '/' . $fileName;

        $file->storeAs('public/uploads/users/' . $this->id, $fileName);

        return Media::create([
            'ref_table' => 'users',
            'ref_id' => $this->id,
            'file_url' => $path,
            'caption' => 'Avatar',
            'mime_type' => $file->getMimeType(),
            'sort_order' => 0
        ]);
    }

    // Method untuk cek role
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function isAdmin()
    {
        return $this->role === 'admin' || $this->role === 'super_admin';
    }
}
