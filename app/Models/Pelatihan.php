<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Pelatihan extends Model
{
    protected $fillable = [
        'nama_pelatihan',
        'deskripsi_pelatihan',
        'image_pelatihan',
        'desk_singkat'
    ];

    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('pelatihans_all');
        });

        static::deleted(function () {
            Cache::forget('pelatihans_all');
        });
    }
}
