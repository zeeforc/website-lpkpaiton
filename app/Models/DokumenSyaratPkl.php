<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class DokumenSyaratPkl extends Model
{
    protected $fillable = [
        'dokumen_syarat_pkl_title',
        'file_path',
    ];

    public function getFileUrlAttribute(): string
    {
        if (! $this->file_path) {
            return '#';
        }

        return asset('storage/'.$this->file_path);
    }

    protected static function booted(): void
    {
        static::saved(function (self $document): void {
            self::retainOnly($document->getKey());
            Cache::forget('syarat_pkl_doc');
        });
        static::deleted(fn () => Cache::forget('syarat_pkl_doc'));
    }

    public static function retainLatest(): int
    {
        $latest = self::query()
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->first();

        return $latest ? self::retainOnly($latest->getKey()) : 0;
    }

    private static function retainOnly(int|string $documentId): int
    {
        $removed = 0;
        $disk = Storage::disk('public');
        $currentPath = self::query()->whereKey($documentId)->value('file_path');

        foreach (self::query()->whereKeyNot($documentId)->get() as $oldDocument) {
            if (
                $oldDocument->file_path
                && $oldDocument->file_path !== $currentPath
                && $disk->exists($oldDocument->file_path)
                && ! $disk->delete($oldDocument->file_path)
            ) {
                throw new RuntimeException("Gagal menghapus dokumen syarat PKL lama: {$oldDocument->file_path}");
            }

            $oldDocument->deleteQuietly();
            $removed++;
        }

        if ($removed > 0) {
            Cache::forget('syarat_pkl_doc');
        }

        return $removed;
    }
}
