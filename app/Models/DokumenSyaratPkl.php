<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class DokumenSyaratPkl extends Model
{
    protected $fillable = [
        'dokumen_syarat_pkl_title',
        'file_path',
    ];

    public function getFileUrlAttribute(): string
    {
        if (! $this->file_path) {
            return '#';
        }

        return asset('storage/' . $this->file_path);
    }

    protected static function booted(): void
    {
        static::saved(fn() => Cache::forget('syarat_pkl_doc'));
        static::deleted(fn() => Cache::forget('syarat_pkl_doc'));
    }
}
