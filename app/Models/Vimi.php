<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vimi extends Model
{
    protected $fillable = [
        'visi_title',
        'visi_text',
        'misi_title',
        'misi_text',
    ];


    protected static function booted(): void
    {
        static::saved(fn() => cache()->forget('vimis'));
        static::deleted(fn() => cache()->forget('vimis'));
    }
}
