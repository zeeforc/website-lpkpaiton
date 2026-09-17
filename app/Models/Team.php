<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    protected $fillable = ['name', 'position', 'photo', 'sort_order'];
    
    protected static function booted(): void
    {
        static::saved(fn() => cache()->forget('home_teams'));
        static::deleted(fn() => cache()->forget('home_teams'));
    }
}
