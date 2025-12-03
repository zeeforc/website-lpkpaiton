<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kurikulum extends Model
{
    protected $fillable = [
        'kurikulum_title',
        'desk_kurikulum',
        'image_kurikulum',
        'desk_singkat',
        'slug',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getImageUrlAttribute(): string
    {
        if (! $this->image_kurikulum) {
            return asset('assets/placeholder.jpg');
        }

        return asset('storage/' . $this->image_kurikulum);
    }

    protected static function booted(): void
    {
        static::saving(function (Kurikulum $kurikulum) {
            // generate slug otomatis dari kurikulum_title jika slug kosong
            if (empty($kurikulum->slug) && ! empty($kurikulum->kurikulum_title)) {
                $base = Str::slug($kurikulum->kurikulum_title);
                $slug = $base;
                $i = 1;

                // pastikan slug unik
                while (
                    static::where('slug', $slug)
                    ->where('id', '!=', $kurikulum->id ?? 0)
                    ->exists()
                ) {
                    $slug = $base . '-' . $i++;
                }

                $kurikulum->slug = $slug;
            }
        });

        // ini hanya jalan kalau kamu memang pakai cache key 'kurikulums_all'
        static::saved(fn() => cache()->forget('kurikulums_all'));
        static::deleted(fn() => cache()->forget('kurikulums_all'));
    }
}
