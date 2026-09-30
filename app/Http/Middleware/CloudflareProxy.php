<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CloudflareProxy
{
    /**
     * cPanel's internal Nginx proxy overwrites X-Forwarded-Proto to 'http'
     * and X-Forwarded-Port to '80', even when user connects via Cloudflare HTTPS.
     *
     * CF-Visitor header is NOT touched by cPanel's proxy, so we use it
     * as the source of truth and correct the forwarded headers before
     * TrustProxies middleware reads them.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cfVisitor = $request->header('CF-Visitor', '');

        if (str_contains($cfVisitor, 'https')) {
            $request->headers->set('X-Forwarded-Proto', 'https');
            $request->headers->set('X-Forwarded-Port', '443');
        }

        return $next($request);
    }
}
