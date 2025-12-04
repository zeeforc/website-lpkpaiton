<?php

namespace App\Models;

use App\Services\ExcelToHtml;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Kurikulum extends Model
{
    protected $fillable = [
        'kurikulum_title',
        'desk_kurikulum',
        'image_kurikulum',
        'desk_singkat',
        'slug',
        'matrix_excel_path',
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

    // matrix_html dihasilkan dari file excel dan di-cache
    public function getMatrixHtmlAttribute(): ?string
    {
        if (! $this->matrix_excel_path) {
            return null;
        }

        $path = storage_path('app/public/' . $this->matrix_excel_path);

        if (! file_exists($path)) {
            return null;
        }

        // key cache unik per kurikulum + path file
        $cacheKey = 'kurikulum_matrix_html_' . $this->id . '_' . md5($this->matrix_excel_path);

        return Cache::rememberForever($cacheKey, function () use ($path) {
            return ExcelToHtml::convert($path);
        });
    }

    protected static function booted(): void
    {
        static::saving(function (Kurikulum $kurikulum) {
            if (empty($kurikulum->slug) && ! empty($kurikulum->kurikulum_title)) {
                $base = Str::slug($kurikulum->kurikulum_title);
                $slug = $base;
                $i = 1;

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

        static::saved(fn() => cache()->forget('kurikulums_all'));
        static::deleted(fn() => cache()->forget('kurikulums_all'));
    }
}
