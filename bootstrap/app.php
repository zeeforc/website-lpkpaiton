<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // CloudflareProxy HARUS jalan duluan untuk memperbaiki header
        // yang ditimpa oleh internal proxy cPanel sebelum TrustProxies membacanya
        $middleware->prepend(\App\Http\Middleware\CloudflareProxy::class);

        $middleware->trustProxies(at: '*');

        // Redirect unauthenticated users to the portal login page
        // instead of the default 'login' route which doesn't exist
        $middleware->redirectGuestsTo(fn () => route('portal.login'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            return redirect()->back()
                ->withInput($request->except('_token'))
                ->with('error', 'Sesi Anda telah berakhir karena tidak aktif. Halaman telah disegarkan, silakan coba lagi.');
        });
    })->create();
