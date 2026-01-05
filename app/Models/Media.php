<?php
// app/Models/Media.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';
    protected $primaryKey = 'media_id';

  protected $fillable = [
        'ref_table',
        'ref_id',
        'file_name',
        'file_url',
        'file_type',
        'file_size',
        'description' // opsional
    ];


    // ===== RELATIONSHIPS =====

    public function persil()
    {
        return $this->belongsTo(Persil::class, 'ref_id')
                    ->where('ref_table', 'persil');
    }

    public function dokumenPersil()
    {
        return $this->belongsTo(DokumenPersil::class, 'ref_id')
                    ->where('ref_table', 'dokumen_persil');
    }

    public function petaPersil()
    {
        return $this->belongsTo(PetaPersil::class, 'ref_id')
                    ->where('ref_table', 'peta_persil');
    }

    public function sengketaPersil()
    {
        return $this->belongsTo(SengketaPersil::class, 'ref_id')
                    ->where('ref_table', 'sengketa_persil');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'ref_id')
                    ->where('ref_table', 'users');
    }

    // ===== CUSTOM ACCESSORS =====

    public function getFileTypeAttribute()
    {
        if (strpos($this->mime_type, 'image/') === 0) {
            return 'image';
        } elseif ($this->mime_type == 'application/pdf') {
            return 'pdf';
        } elseif (in_array($this->mime_type, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
            return 'word';
        } elseif (in_array($this->mime_type, ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'])) {
            return 'excel';
        } else {
            return 'other';
        }
    }

    public function getFileSizeAttribute()
    {
        if (file_exists(storage_path('app/public/' . $this->file_url))) {
            $size = filesize(storage_path('app/public/' . $this->file_url));
            return $this->formatBytes($size);
        }
        return '0 KB';
    }

    public function getThumbnailAttribute()
    {
        if ($this->file_type == 'image') {
            return asset('storage/' . $this->file_url);
        }

        $icons = [
            'pdf' => 'file-pdf',
            'word' => 'file-word',
            'excel' => 'file-excel',
            'other' => 'file'
        ];

        return $icons[$this->file_type] ?? 'file';
    }

    // ===== SCOPES =====

    public function scopeWhereRef($query, $table, $id)
    {
        return $query->where('ref_table', $table)->where('ref_id', $id);
    }

    public function scopeImages($query)
    {
        return $query->where('mime_type', 'LIKE', 'image/%');
    }

    public function scopeDocuments($query)
    {
        return $query->where('mime_type', 'NOT LIKE', 'image/%');
    }

    // ===== HELPER METHODS =====

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
