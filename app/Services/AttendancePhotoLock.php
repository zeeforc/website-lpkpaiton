<?php

namespace App\Services;

use RuntimeException;

class AttendancePhotoLock
{
    public function acquire(bool $blocking): mixed
    {
        $path = storage_path('framework/attendance-photos.lock');
        $handle = fopen($path, 'c');

        if ($handle === false) {
            throw new RuntimeException('Gagal membuka lock foto absensi.');
        }

        $operation = LOCK_EX | ($blocking ? 0 : LOCK_NB);
        if (! flock($handle, $operation)) {
            fclose($handle);

            return null;
        }

        return $handle;
    }

    public function release(mixed $handle): void
    {
        if (is_resource($handle)) {
            flock($handle, LOCK_UN);
            fclose($handle);
        }
    }
}
