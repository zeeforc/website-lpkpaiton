<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ApplicationDocument extends Model
{
    protected $fillable = ['application_id', 'file_path', 'original_name', 'status', 'keterangan'];

    public static function storeApplicantUpload(
        UploadedFile $file,
        Application $application,
        string $documentType,
        string $directory,
    ): array {
        $extension = strtolower($file->extension());
        $path = self::applicantStoragePath($application, $documentType, $extension, $directory);
        $filename = basename($path);

        $storedPath = $file->storeAs(trim($directory, '/'), $filename, 'public');

        if (! is_string($storedPath)) {
            throw new RuntimeException('Gagal menyimpan dokumen pendaftar.');
        }

        return [
            'file_path' => $storedPath,
            'original_name' => $documentType.' - '.$filename,
        ];
    }

    public static function applicantStoragePath(
        Application $application,
        string $documentType,
        string $extension,
        string $directory,
        ?string $ignoreExistingPath = null,
        array $reservedPaths = [],
    ): string {
        $documentTitle = self::titleForType($documentType);
        $applicantName = substr(Str::slug($application->nama_lengkap, '_') ?: 'peserta', 0, 120);
        $fileBaseName = Str::slug($documentTitle, '_').'_'.$applicantName;
        $extension = Str::slug(strtolower(trim($extension, '. ')));
        $directory = trim($directory, '/');
        $suffix = 1;

        do {
            $suffixPart = $suffix === 1 ? '' : '_'.$suffix;
            $filename = $fileBaseName.$suffixPart.($extension === '' ? '' : '.'.$extension);
            $path = $directory === '' ? $filename : "{$directory}/{$filename}";
            $suffix++;
        } while (
            in_array($path, $reservedPaths, true)
            || ($path !== $ignoreExistingPath && Storage::disk('public')->exists($path))
        );

        return $path;
    }

    public static function titleForType(string $documentType): string
    {
        return match ($documentType) {
            'Surat Pengantar Sekolah/Kampus' => 'Surat pengantar sekolah atau kampus',
            'KTP/Kartu Pelajar' => 'Fotokopi KTP / Kartu Pelajar',
            'Pas Foto 4x6' => 'Pas Foto 4x6 Latar Belakang Merah',
            'SKCK' => 'Surat Kelakuan Baik (Siswa) / SKCK (Mahasiswa)',
            'Surat Sehat' => 'Surat Keterangan Sehat',
            'Portofolio' => 'Portofolio',
            'Surat Perjanjian PKL' => 'Surat Perjanjian PKL (Telah Ditandatangani)',
            'Dokumen Tambahan' => 'BPJS Kesehatan',
            default => $documentType,
        };
    }

    public static function typeFromOriginalName(string $originalName): string
    {
        if (str_starts_with($originalName, 'Surat Pengantar - ')) {
            return 'Surat Pengantar Sekolah/Kampus';
        }

        foreach ([
            'Surat Pengantar Sekolah/Kampus',
            'KTP/Kartu Pelajar',
            'Pas Foto 4x6',
            'SKCK',
            'Surat Sehat',
            'Portofolio',
            'Surat Perjanjian PKL',
            'Dokumen Tambahan',
        ] as $documentType) {
            if (str_starts_with($originalName, $documentType.' - ')) {
                return $documentType;
            }
        }

        return 'Surat Pengantar Sekolah/Kampus';
    }

    public function getDocumentTitleAttribute(): string
    {
        return self::titleForType(self::typeFromOriginalName($this->original_name));
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
