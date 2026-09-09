@extends('errors::minimal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message', __('Halaman Kadaluarsa. Menyegarkan...'))

<script>
    // Self-healing mechanism for PWA cache issues
    // If user hits 419, it's highly likely their SW is caching an old CSRF token.
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.getRegistrations().then(function(registrations) {
            for (let registration of registrations) {
                registration.unregister();
            }
            // Add a small delay to ensure unregistration completes before redirecting
            setTimeout(() => {
                window.location.href = '/portal/login?v=' + new Date().getTime();
            }, 1500);
        }).catch(function() {
            setTimeout(() => {
                window.location.href = '/portal/login?v=' + new Date().getTime();
            }, 1500);
        });
    } else {
        setTimeout(() => {
            window.location.href = '/portal/login?v=' + new Date().getTime();
        }, 1500);
    }
</script>
