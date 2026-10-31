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

        $webp = (new ImageOptimizer())->toWebp($png, 'image/png');
        $imageInfo = getimagesizefromstring($webp);

        $this->assertSame('image/webp', $imageInfo['mime']);
        $this->assertSame(1920, $imageInfo[0]);
        $this->assertSame(960, $imageInfo[1]);
    }

    public function test_it_rejects_invalid_image_data(): void
    {
        $this->expectException(InvalidArgumentException::class);

        (new ImageOptimizer())->toWebp('not an image');
    }

    public function test_it_rejects_a_mime_type_that_does_not_match_the_image(): void
    {
        $image = imagecreatetruecolor(1, 1);
        ob_start();
        imagepng($image);
        $png = ob_get_clean();
        imagedestroy($image);

        $this->expectException(InvalidArgumentException::class);

        (new ImageOptimizer())->toWebp($png, 'image/jpeg');
    }
}
