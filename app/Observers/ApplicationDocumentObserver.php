<?php

namespace App\Observers;

use App\Models\ApplicationDocument;
use App\Mail\ApplicationStatusUpdated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ApplicationDocumentObserver
{
    /**
     * Track applications that have already been notified in this request lifecycle
     * to prevent spamming multiple emails when multiple documents are updated at once.
     */
    public static array $notifiedApplications = [];

    public function updated(ApplicationDocument $document): void
    {
        if ($document->wasChanged(['status', 'keterangan'])) {
            $application = $document->application;
            
            // Prevent duplicate emails in the same request
            if (!in_array($application->id, self::$notifiedApplications)) {
                
                // Only send if the application is not already fully accepted/rejected
                if (in_array($application->status, ['permohonan_diterima', 'document_review', 'revisi_dokumen'])) {
                    
                    Log::info("Document status changed. Sending email for application: {$application->id}");
                    
                    try {
                        Mail::to($application->email_balasan)
                            ->send(new ApplicationStatusUpdated($application, null, null));
                        Log::info("Document update email successfully sent via SMTP to {$application->email_balasan}");
                    } catch (\Exception $e) {
                        Log::error("Failed to send document update email: " . $e->getMessage());
                    }

                    // Mark as notified in this request
                    self::$notifiedApplications[] = $application->id;
                }
            }
        }
    }
}
