<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\Application::observe(\App\Observers\ApplicationObserver::class);
        \App\Models\ApplicationDocument::observe(\App\Observers\ApplicationDocumentObserver::class);
        \App\Models\LeaveRequest::observe(\App\Observers\LeaveRequestObserver::class);
        Paginator::useBootstrapFive();

        // Fix: cPanel internal proxy menimpa X-Forwarded-Proto jadi 'http'
        // padahal user akses via HTTPS lewat Cloudflare.
        // CF-Visitor header TIDAK ditimpa oleh cPanel, jadi kita pakai itu
        // untuk memperbaiki X-Forwarded-Proto sebelum TrustProxies membacanya.
        $cfVisitor = request()->header('CF-Visitor', '');
        if (str_contains($cfVisitor, 'https')) {
            request()->headers->set('X-Forwarded-Proto', 'https');
            request()->server->set('HTTPS', 'on');
            request()->server->set('SERVER_PORT', 443);
            request()->server->set('REQUEST_SCHEME', 'https');
        }

        if (app()->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
