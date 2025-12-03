<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SaranaSatu extends Model
{
    protected $fillable = ['sarana_desk'];

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
