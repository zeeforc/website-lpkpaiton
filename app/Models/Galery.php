<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Galery extends Model
{
    protected $fillable = ['galery_image'];

    protected $casts = [
        'galery_image' => 'array'
    ];

    protected static function booted(): void
    {
        static::saved(fn() => cache()->forget('galeries-image'));
        static::deleted(fn() => cache()->forget('galeries-image'));
    }
}
