<?php

namespace App\Services;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;
use ZipStream\CompressionMethod;
use ZipStream\Stream\CallbackStreamWrapper;
use ZipStream\ZipStream;

class AttendanceDocumentationExporter
{
    public function __construct(private AttendancePhotoLock $lock) {}

    public function download(int $year, int $month): StreamedResponse
    {
        $lockHandle = $this->lock->acquire(false);
        if ($lockHandle === null) {
            abort(503, 'Foto absensi sedang diproses. Silakan coba unduh kembali sebentar lagi.');
        }

        $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();
        $query = $this->query($start, $end);

        if (! (clone $query)->exists()) {
            $this->lock->release($lockHandle);
            abort(404, 'Tidak ada dokumentasi karyawan untuk periode tersebut.');
        }

        $downloadName = 'Dokumentasi Karyawan - '.$start->translatedFormat('F Y').'.zip';

        return response()->streamDownload(
            function () use ($query, $lockHandle): void {
                try {
                    $this->streamArchive($query);
                } finally {
                    $this->lock->release($lockHandle);
                }
            },
            $downloadName,
            [
                'Content-Type' => 'application/zip',
                'Cache-Control' => 'private, no-store, no-cache, must-revalidate',
                'X-Accel-Buffering' => 'no',
            ],
        );
    }

    private function query(Carbon $start, Carbon $end)
    {
        return Attendance::query()
            ->whereHas('user', fn ($query) => $query->whereIn('role', ['karyawan_paving', 'instruktur_lpk']))
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->where(fn ($query) => $query->whereNotNull('photo_path')->orWhereNotNull('checkout_photos'))
            ->with('user')
            ->orderBy('id');
    }

    private function streamArchive($query): void
    {
        set_time_limit(0);

        $output = CallbackStreamWrapper::open(static function (string $chunk): void {
            echo $chunk;
            if (ob_get_level() > 0) {
                ob_flush();
            }
            flush();
        });

        if (! is_resource($output)) {
            throw new \RuntimeException('Gagal membuka output stream untuk ZIP dokumentasi.');
        }

        $zip = null;
        try {
            $zip = new ZipStream(
                outputStream: $output,
                defaultCompressionMethod: CompressionMethod::STORE,
                defaultEnableZeroHeader: false,
                sendHttpHeaders: false,
                flushOutput: true,
            );

            $disk = Storage::disk('public');
            $query->chunkById(50, function ($attendances) use ($zip, $disk): void {
                foreach ($attendances as $attendance) {
                    if (! $attendance->user) {
                        continue;
                    }

                    $userName = preg_replace('/[^A-Za-z0-9\-\s]/', '', $attendance->user->name ?? 'Unknown');
                    $role = $attendance->user->role === 'instruktur_lpk' ? 'Instruktur LPK' : 'Karyawan Paving';
                    $folder = trim($userName).' - '.$role.' ('.$attendance->user_id.')';
                    $date = Carbon::parse($attendance->date);
                    $dateFolder = strtolower($date->translatedFormat('d-F'));
                    $archiveFolder = $folder.'/'.$dateFolder;

                    if ($attendance->photo_path) {
                        $checkInDate = $date->copy();
                        if ($attendance->check_in) {
                            $checkInDate->setTimeFromTimeString((string) $attendance->check_in);
                        }

                        $extension = $this->extension($attendance->photo_path);
                        if ($extension !== null) {
                            $this->addFile(
                                $zip,
                                $disk,
                                $attendance->photo_path,
                                $archiveFolder.'/'.$checkInDate->format('Y-m-d_H-i-s').'_Masuk.'.$extension,
                            );
                        }
                    }

                    if (! $attendance->checkout_photos) {
                        continue;
                    }

                    $photos = is_array($attendance->checkout_photos)
                        ? $attendance->checkout_photos
                        : json_decode($attendance->checkout_photos, true);

                    if (! is_array($photos)) {
                        Log::warning('Skipped invalid checkout photo references during streamed export.', [
                            'attendance_id' => $attendance->id,
                        ]);

                        continue;
                    }

                    $checkOutDate = $date->copy();
                    if ($attendance->check_out) {
                        $checkOutDate->setTimeFromTimeString((string) $attendance->check_out);
                    } else {
                        $checkOutDate->setTime(16, 0, 0);
                    }

                    $counter = 1;
                    foreach ($photos as $path) {
                        if (! is_string($path)) {
                            continue;
                        }

                        $extension = $this->extension($path);
                        if ($extension === null) {
                            continue;
                        }

                        $this->addFile(
                            $zip,
                            $disk,
                            $path,
                            $archiveFolder.'/'.$checkOutDate->format('Y-m-d_H-i-s').'_Dokumentasi_'.$counter.'.'.$extension,
                        );
                        $counter++;
                    }
                }
            });

            $zip->finish();
        } catch (Throwable $exception) {
            Log::error('Streamed attendance documentation ZIP failed.', ['exception' => $exception]);
            throw $exception;
        } finally {
            if (is_resource($output)) {
                fclose($output);
            }
        }
    }

    private function extension(string $path): ?string
    {
        if (! Str::startsWith($path, 'attendances/') || in_array('..', explode('/', $path), true)) {
            Log::warning('Skipped unsafe attendance photo path in streamed export.', ['path' => $path]);

            return null;
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true) ? $extension : null;
    }

    private function addFile(ZipStream $zip, Filesystem $disk, string $path, string $archivePath): void
    {
        $root = realpath($disk->path(''));
        $file = realpath($disk->path($path));

        if (
            $root === false
            || $file === false
            || ! str_starts_with($file, $root.DIRECTORY_SEPARATOR)
            || ! is_file($file)
            || ! is_readable($file)
        ) {
            Log::warning('Skipped missing or unsafe attendance photo during streamed export.', ['path' => $path]);

            return;
        }

        $zip->addFileFromPath(
            fileName: $archivePath,
            path: $file,
            compressionMethod: CompressionMethod::STORE,
        );
    }
}
