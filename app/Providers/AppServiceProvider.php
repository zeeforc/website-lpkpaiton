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

        // Memaksa HTTPS jika bukan di local/Laragon agar upload tidak diblokir
        if (!request()->is('localhost*') && !request()->is('127.0.0.1*') && !str_contains(request()->url(), 'lpkpaiton.test')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
