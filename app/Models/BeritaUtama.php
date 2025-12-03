<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BeritaUtama extends Model
{
    protected $table = 'berita_utamas';

    protected $fillable = [
        'berita_utama_title',
        'berita_utama_image',
        'berita_utama_desk',
        'tgl_berita',
        'slug',
    ];

    public function getImageUrlAttribute(): string
    {
        if (! $this->berita_utama_image) {
            return asset('assets/placeholder.jpg');
        }

        return asset('storage/' . $this->berita_utama_image);
    }

    protected static function booted(): void
    {
        static::saving(function (BeritaUtama $berita) {
            if (empty($berita->slug) && ! empty($berita->berita_utama_title)) {
                $base = Str::slug($berita->berita_utama_title);
                $slug = $base;
                $i = 1;

                while (
                    static::where('slug', $slug)
                    ->where('id', '!=', $berita->id ?? 0)
                    ->exists()
                ) {
                    $slug = $base . '-' . $i++;
                }

                $berita->slug = $slug;
            }
        });

        static::saved(fn() => cache()->forget('berita_latest'));
        static::deleted(fn() => cache()->forget('berita_latest'));
    }
}
