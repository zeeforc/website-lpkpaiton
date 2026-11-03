<?php

namespace Tests\Unit;

use App\Services\ImageOptimizer;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ImageOptimizerTest extends TestCase
{
    public function test_it_resizes_and_converts_a_png_to_webp(): void
    {
        $image = imagecreatetruecolor(2400, 1200);
        ob_start();
        imagepng($image);
        $png = ob_get_clean();
        imagedestroy($image);

        $webp = (new ImageOptimizer)->toWebp($png, 'image/png');
        $imageInfo = getimagesizefromstring($webp);

        $this->assertSame('image/webp', $imageInfo['mime']);
        $this->assertSame(1920, $imageInfo[0]);
        $this->assertSame(960, $imageInfo[1]);
    }

    public function test_it_rejects_invalid_image_data(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new ImageOptimizer)->toWebp('not an image');
    }

    public function test_it_rejects_a_mime_type_that_does_not_match_the_image(): void
    {
        $image = imagecreatetruecolor(1, 1);
        ob_start();
        imagepng($image);
        $png = ob_get_clean();
        imagedestroy($image);

        $this->expectException(InvalidArgumentException::class);

        (new ImageOptimizer)->toWebp($png, 'image/jpeg');
    }

    public function test_it_rejects_base64_uploads_larger_than_one_megabyte(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('melebihi 1 MB');

        (new ImageOptimizer)->fromDataUri('data:image/jpeg;base64,'.str_repeat('A', 1400000));
    }

    public function test_webp_output_never_exceeds_one_hundred_kilobytes(): void
    {
        $image = imagecreatetruecolor(800, 600);
        mt_srand(1234);

        for ($y = 0; $y < 600; $y++) {
            for ($x = 0; $x < 800; $x++) {
                $color = (mt_rand(0, 255) << 16) | (mt_rand(0, 255) << 8) | mt_rand(0, 255);
                imagesetpixel($image, $x, $y, $color);
            }
        }

        ob_start();
        imagejpeg($image, null, 90);
        $jpeg = ob_get_clean();
        imagedestroy($image);

        $webp = (new ImageOptimizer)->toWebp($jpeg, 'image/jpeg');

        $this->assertLessThanOrEqual(100 * 1024, strlen($webp));
        $this->assertSame('image/webp', getimagesizefromstring($webp)['mime']);
    }
}
