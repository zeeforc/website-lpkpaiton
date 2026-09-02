<?php

namespace App\Observers;

use App\Models\Application;
use App\Mail\ApplicationStatusUpdated;
use Illuminate\Support\Facades\Mail;

class ApplicationObserver
{
    public function updated(Application $application): void
    {
        \Illuminate\Support\Facades\Log::info("Observer FIRED for application: {$application->id}");
        \Illuminate\Support\Facades\Log::info("Changes array: " . json_encode($application->getChanges()));

        if ($application->wasChanged('status')) {
            \Illuminate\Support\Facades\Log::info("Status was changed to {$application->status}. Sending email...");
            $note = null;
            if ($application->status === 'rejected') {
                $note = $application->notes()->latest()->first()?->note;
            }

            $password = null;
            if ($application->status === 'accepted') {
                $password = \Illuminate\Support\Str::random(8);
                if ($application->user) {
                    $application->user->update([
                        'password' => \Illuminate\Support\Facades\Hash::make($password)
                    ]);
                }
            }

            try {
                Mail::to($application->email_balasan)
                    ->send(new ApplicationStatusUpdated($application, $note, $password));
                \Illuminate\Support\Facades\Log::info("Email successfully sent via SMTP to {$application->email_balasan}");
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send email: " . $e->getMessage());
            }
        }
    }
}
