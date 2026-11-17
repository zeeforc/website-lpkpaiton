<?php

namespace Tests\Unit;

use App\Rules\MaxTotalFileSize;
use Illuminate\Http\UploadedFile;
use PHPUnit\Framework\TestCase;

class MaxTotalFileSizeTest extends TestCase
{
    public function test_it_accepts_two_files_whose_combined_size_is_five_megabytes(): void
    {
        $errors = [];
        $rule = new MaxTotalFileSize(5 * 1024 * 1024, 'Total size is too large.');

        $rule->validate('photos', [
            UploadedFile::fake()->create('first.jpg', 2560),
            UploadedFile::fake()->create('second.jpg', 2560),
        ], function (string $message) use (&$errors): void {
            $errors[] = $message;
        });

        $this->assertSame([], $errors);
    }

    public function test_it_rejects_two_files_whose_combined_size_exceeds_five_megabytes(): void
    {
        $errors = [];
        $rule = new MaxTotalFileSize(5 * 1024 * 1024, 'Total size is too large.');

        $rule->validate('photos', [
            UploadedFile::fake()->create('first.jpg', 2561),
            UploadedFile::fake()->create('second.jpg', 2560),
        ], function (string $message) use (&$errors): void {
            $errors[] = $message;
        });

        $this->assertSame(['Total size is too large.'], $errors);
    }
}
