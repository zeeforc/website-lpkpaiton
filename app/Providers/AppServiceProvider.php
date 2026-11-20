<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        \App\Models\Application::observe(\App\Observers\ApplicationObserver::class);
        \App\Models\ApplicationDocument::observe(\App\Observers\ApplicationDocumentObserver::class);
        \App\Models\LeaveRequest::observe(\App\Observers\LeaveRequestObserver::class);
        Paginator::useBootstrapFive();

        if (app()->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
