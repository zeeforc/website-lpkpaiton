<?php

namespace App\Observers;

use App\Models\ApplicationDocument;
use App\Mail\ApplicationStatusUpdated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ApplicationDocumentObserver
{
    public static array $notifiedApplications = [];

    public function updated(ApplicationDocument $document): void
    {
        if ($document->wasChanged(['status', 'keterangan'])) {
            $application = $document->application;
            
            if ($application->status !== 'accepted' && $application->status !== 'rejected') {
                $requiredDocs = ['KTP/Kartu Pelajar', 'Pas Foto 4x6', 'SKCK', 'Surat Sehat'];
                $allDocsValid = true;
                
                $documents = $application->documents()->get();
                
                foreach ($requiredDocs as $reqDoc) {
                    $doc = $documents->first(function($d) use ($reqDoc) {
                        return str_starts_with($d->original_name, $reqDoc . ' -');
                    });
                    
                    if (!$doc || $doc->status !== 'Valid') {
                        $allDocsValid = false;
                        break;
                    }
                }
                
                if ($allDocsValid) {
                    Log::info("All required documents for application {$application->id} are Valid. Auto-accepting.");
                    $application->update(['status' => 'accepted']);
                    return; 
                }
            }
            
            if (!in_array($application->id, self::$notifiedApplications)) {
                
                if (in_array($application->status, ['permohonan_diterima', 'document_review', 'revisi_dokumen'])) {
                    
                    Log::info("Document status changed. Sending email for application: {$application->id}");
                    
                    try {
                        Mail::to($application->email_balasan)
                            ->send(new ApplicationStatusUpdated($application, null, null));
                        Log::info("Document update email successfully sent via SMTP to {$application->email_balasan}");
                    } catch (\Exception $e) {
                        Log::error("Failed to send document update email: " . $e->getMessage());
                    }

                    self::$notifiedApplications[] = $application->id;
                }
            }
        }
    }
}
