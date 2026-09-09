const CACHE_NAME = 'presensi-cache-v1';
const urlsToCache = [
  '/',
  '/portal/login',
  '/manifest.json',
  '/images/app_icon.png',
  '/images/logo_yayasan.png'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        if (response) {
          return response;
        }
        return fetch(event.request).catch(() => {
           // Provide fallback if offline
        });
      })
  );
});
