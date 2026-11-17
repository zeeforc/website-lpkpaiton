<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;

class MaxTotalFileSize implements ValidationRule
{
    public function __construct(
        private readonly int $maxBytes,
        private readonly string $message,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_array($value)) {
            return;
        }

        $totalBytes = 0;
        foreach ($value as $file) {
            if ($file instanceof UploadedFile) {
                $totalBytes += (int) $file->getSize();
            }
        }

        if ($totalBytes > $this->maxBytes) {
            $fail($this->message);
        }
    }
}
