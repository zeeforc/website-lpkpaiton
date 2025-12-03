<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Home extends Model
{
    protected $fillable = ['title', 'bg_image'];
    
    protected static function booted(): void
    {
        static::saved(function () {
            cache()->forget('home_hero');
        });

        static::deleted(function () {
            cache()->forget('home_hero');
        });
    }
}
