<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Sarana extends Model
{
    protected $fillable = ['fasilitas_title', 'fasilitas_image', 'fasilitas_desk'];

    public function getImageUrlAttribute(): string
    {
        $path = $this->fasilitas_image;

        if (! $path) {
            return asset('assets/placeholder.jpg');
        }

        // Kalau yang tersimpan sudah URL penuh
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        // Kalau sudah ada folder di depannya (mis: "sarana/xxx.jpg")
        if (str_contains($path, '/')) {
            return asset('storage/' . $path);
        }

        // Kalau hanya nama file saja
        return asset('storage/sarana/' . $path);
    }

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('sarana_prasarana_detail');
        });

        static::deleted(function () {
            Cache::forget('sarana_prasarana_detail');
        });
    }
}
