<?php

namespace App\Services;

use InvalidArgumentException;
use RuntimeException;
use Throwable;

class ImageOptimizer
{
    private const MAX_DIMENSION = 1920;

    private const MAX_PIXELS = 12000000;

    private const MAX_INPUT_BYTES = 1024 * 1024;

    private const MAX_OUTPUT_BYTES = 100 * 1024;

    public function fromDataUri(string $dataUri): string
    {
        if (! preg_match('/\Adata:image\/(jpeg|jpg|png|webp);base64,([A-Za-z0-9+\/=\r\n]+)\z/i', $dataUri, $matches)) {
            throw new InvalidArgumentException('Format foto tidak valid.');
        }

        $image = base64_decode($matches[2], true);

        if ($image === false || strlen($image) > self::MAX_INPUT_BYTES) {
            throw new InvalidArgumentException('Data foto tidak valid atau melebihi 1 MB.');
        }

        $expectedMime = strtolower($matches[1]) === 'jpg' ? 'image/jpeg' : 'image/'.strtolower($matches[1]);

        return $this->toWebp($image, $expectedMime);
    }

    public function toWebp(string $image, ?string $expectedMime = null): string
    {
        if (! function_exists('imagewebp')) {
            throw new RuntimeException('Server PHP belum mendukung encoding WebP melalui GD.');
        }

        $imageInfo = @getimagesizefromstring($image);
        $mime = $imageInfo['mime'] ?? null;

        if (! in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            throw new InvalidArgumentException('File yang diunggah bukan gambar JPEG, PNG, atau WebP yang valid.');
        }

        if ($expectedMime !== null && $this->normalizeMime($expectedMime) !== $mime) {
            throw new InvalidArgumentException('Tipe gambar tidak sesuai dengan konten file.');
        }

        [$width, $height] = [$imageInfo[0] ?? 0, $imageInfo[1] ?? 0];

        if ($width < 1 || $height < 1 || ($width * $height) > self::MAX_PIXELS) {
            throw new InvalidArgumentException('Resolusi foto tidak valid atau terlalu besar untuk diproses.');
        }

        $source = @imagecreatefromstring($image);

        if ($source === false) {
            throw new InvalidArgumentException('Foto tidak dapat dibaca sebagai gambar.');
        }

        try {
            $scale = min(1, self::MAX_DIMENSION / max($width, $height));
            $targetWidth = max(1, (int) round($width * $scale));
            $targetHeight = max(1, (int) round($height * $scale));

            while (true) {
                $target = imagecreatetruecolor($targetWidth, $targetHeight);
                if ($target === false) {
                    throw new RuntimeException('Server tidak memiliki memori cukup untuk memproses foto.');
                }

                imagealphablending($target, false);
                imagesavealpha($target, true);

                if (! imagecopyresampled($target, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height)) {
                    imagedestroy($target);
                    throw new RuntimeException('Server gagal mengubah ukuran foto.');
                }

                foreach ([80, 70, 60, 50, 40, 30] as $quality) {
                    $webp = $this->encodeWebp($target, $quality);
                    if (strlen($webp) <= self::MAX_OUTPUT_BYTES) {
                        imagedestroy($target);

                        return $webp;
                    }
                }

                imagedestroy($target);

                if ($targetWidth === 1 && $targetHeight === 1) {
                    throw new RuntimeException('Server gagal menghasilkan WebP di bawah batas 100 KB.');
                }

                $targetWidth = max(1, (int) floor($targetWidth * 0.8));
                $targetHeight = max(1, (int) floor($targetHeight * 0.8));
            }
        } finally {
            imagedestroy($source);
        }
    }

    private function encodeWebp(\GdImage $image, int $quality): string
    {
        $bufferLevel = ob_get_level();
        ob_start();

        try {
            if (! imagewebp($image, null, $quality)) {
                throw new RuntimeException('Server gagal mengonversi foto ke WebP.');
            }

            $webp = ob_get_clean();
            if (! is_string($webp) || $webp === '') {
                throw new RuntimeException('Server menghasilkan file WebP kosong.');
            }

            return $webp;
        } catch (Throwable $exception) {
            while (ob_get_level() > $bufferLevel) {
                ob_end_clean();
            }

            throw $exception;
        }
    }

    private function normalizeMime(string $mime): string
    {
        return strtolower($mime) === 'image/jpg' ? 'image/jpeg' : strtolower($mime);
    }
}
