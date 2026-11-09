<?php

namespace App\Console\Commands;

use App\Models\ApplicationDocument;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class RenameApplicationDocuments extends Command
{
    protected $signature = 'app:rename-application-documents {--force : Rename existing files and update their database records}';

    protected $description = 'Rename existing application documents using their document type and applicant name';

    public function handle(): int
    {
        $disk = Storage::disk('public');
        $processed = 0;
        $missing = 0;
        $reservedPaths = [];

        ApplicationDocument::query()
            ->with('application')
            ->chunkById(100, function ($documents) use ($disk, &$processed, &$missing, &$reservedPaths): void {
                foreach ($documents as $document) {
                    $application = $document->application;
                    if (! $application) {
                        $this->error("Document {$document->id} has no associated application.");
                        $missing++;

                        continue;
                    }

                    if (! $disk->exists($document->file_path)) {
                        $this->warn("Document {$document->id} is missing from storage: {$document->file_path}");
                        $missing++;

                        continue;
                    }

                    $documentType = ApplicationDocument::typeFromOriginalName($document->original_name);
                    $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
                    $directory = dirname($document->file_path);
                    $directory = $directory === '.' ? '' : $directory;
                    $targetPath = ApplicationDocument::applicantStoragePath(
                        $application,
                        $documentType,
                        $extension,
                        $directory,
                        $document->file_path,
                        $reservedPaths,
                    );
                    $targetName = basename($targetPath);
                    $targetOriginalName = $documentType.' - '.$targetName;
                    $reservedPaths[] = $targetPath;

                    if ($document->file_path === $targetPath && $document->original_name === $targetOriginalName) {
                        continue;
                    }

                    if (! $this->option('force')) {
                        $this->line("Document {$document->id}: {$document->file_path} -> {$targetPath}");
                        $processed++;

                        continue;
                    }

                    $previousPath = $document->file_path;
                    $fileMoved = $previousPath !== $targetPath;

                    try {
                        DB::transaction(function () use ($document, $disk, $fileMoved, $previousPath, $targetPath, $targetOriginalName): void {
                            if ($fileMoved) {
                                if ($disk->exists($targetPath) || ! $disk->move($previousPath, $targetPath)) {
                                    throw new RuntimeException("Unable to move document {$document->id} to {$targetPath}.");
                                }
                            }

                            $document->file_path = $targetPath;
                            $document->original_name = $targetOriginalName;

                            if (! $document->save()) {
                                throw new RuntimeException("Unable to update document {$document->id}.");
                            }
                        });
                    } catch (Throwable $exception) {
                        if ($fileMoved && $disk->exists($targetPath) && ! $disk->exists($previousPath)) {
                            if (! $disk->move($targetPath, $previousPath)) {
                                throw new RuntimeException(
                                    "Failed to restore document {$document->id} after its database update failed.",
                                    previous: $exception,
                                );
                            }
                        }

                        throw $exception;
                    }

                    $this->info("Renamed document {$document->id} to {$targetPath}");
                    $processed++;
                }
            });

        if (! $this->option('force')) {
            $this->info("Dry run complete. {$processed} document(s) can be renamed; {$missing} missing or invalid record(s) were skipped.");
            $this->line('Re-run with --force to apply the changes.');
        } else {
            $this->info("Renamed {$processed} document(s); {$missing} missing or invalid record(s) were skipped.");
        }

        return $missing > 0 ? self::FAILURE : self::SUCCESS;
    }
}
