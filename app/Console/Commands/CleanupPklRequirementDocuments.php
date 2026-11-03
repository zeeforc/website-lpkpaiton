<?php

namespace App\Console\Commands;

use App\Models\DokumenSyaratPkl;
use Illuminate\Console\Command;

class CleanupPklRequirementDocuments extends Command
{
    protected $signature = 'app:cleanup-pkl-requirement-documents';

    protected $description = 'Keep only the latest PKL requirement document';

    public function handle(): int
    {
        $removed = DokumenSyaratPkl::retainLatest();

        $this->info("Removed {$removed} older PKL requirement document(s).");

        return self::SUCCESS;
    }
}
