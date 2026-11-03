<?php

namespace App\Services;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class AttendanceExportStorage
{
    public function clearPreviousExports(): int
    {
        $deleted = 0;
        $privateDisk = Storage::disk('local');
        $publicDisk = Storage::disk('public');

        foreach ($privateDisk->files('attendance-exports') as $path) {
            if ($this->isPrivateExport($path)) {
                $this->deleteOrFail($privateDisk, $path);
                $deleted++;
            }
        }

        foreach ($publicDisk->files() as $path) {
            if ($this->isLegacyExport($path)) {
                $this->deleteOrFail($publicDisk, $path);
                $deleted++;
            }
        }

        return $deleted;
    }

    public function deleteExpiredExports(int $olderThan): int
    {
        $deleted = 0;
        $privateDisk = Storage::disk('local');
        $publicDisk = Storage::disk('public');

        foreach ($privateDisk->files('attendance-exports') as $path) {
            if ($this->isPrivateExport($path)) {
                $deleted += $this->deleteIfExpired($privateDisk, $path, $olderThan) ? 1 : 0;
            }
        }

        foreach ($publicDisk->files() as $path) {
            if ($this->isLegacyExport($path)) {
                $deleted += $this->deleteIfExpired($publicDisk, $path, $olderThan) ? 1 : 0;
            }
        }

        return $deleted;
    }

    private function isLegacyExport(string $path): bool
    {
        $name = basename($path);

        return preg_match('/\ADokumentasi Karyawan - .+\.zip(?:\.[A-Za-z0-9]+)?\.part\z/i', $name) === 1
            || preg_match('/\ADokumentasi Karyawan - .+\.zip\z/i', $name) === 1;
    }

    private function isPrivateExport(string $path): bool
    {
        return preg_match(
            '/\A[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}\.zip(?:\.[A-Za-z0-9]+\.part)?\z/i',
            basename($path),
        ) === 1;
    }

    private function deleteIfExpired(Filesystem $disk, string $path, int $olderThan): bool
    {
        try {
            if ($disk->lastModified($path) >= $olderThan) {
                return false;
            }

            if (! $disk->delete($path)) {
                Log::error('Failed to delete a stale attendance documentation export.', ['path' => $path]);

                return false;
            }

            return true;
        } catch (Throwable $exception) {
            Log::error('Failed to clean up a stale attendance documentation export.', [
                'path' => $path,
                'exception' => $exception,
            ]);

            return false;
        }
    }

    private function deleteOrFail(Filesystem $disk, string $path): void
    {
        if (! $disk->delete($path)) {
            throw new RuntimeException("Gagal menghapus arsip dokumentasi lama: {$path}");
        }
    }
}
