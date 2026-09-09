const CACHE_NAME = 'presensi-cache-v2';
const urlsToCache = [
  '/manifest.json',
  '/images/app_icon.png',
  '/images/logo_yayasan.png'
];

self.addEventListener('install', event => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(urlsToCache))
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames
          .filter(name => name !== CACHE_NAME)
          .map(name => caches.delete(name))
      );
    }).then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', event => {
  const url = new URL(event.request.url);

  // Jangan intercept request navigasi (halaman HTML) dan POST request
  // Ini mencegah CSRF token basi yang menyebabkan 419 Page Expired
  if (event.request.mode === 'navigate' || event.request.method !== 'GET') {
    return;
  }

  // Hanya cache aset statis (gambar, font, manifest)
  const isStaticAsset = url.pathname.startsWith('/images/') ||
                        url.pathname.startsWith('/fonts/') ||
                        url.pathname.startsWith('/css/') ||
                        url.pathname.startsWith('/js/') ||
                        url.pathname === '/manifest.json';

  if (!isStaticAsset) {
    return;
  }

  event.respondWith(
    caches.match(event.request)
      .then(response => {
        if (response) {
          return response;
        }
        return fetch(event.request).then(networkResponse => {
          if (networkResponse && networkResponse.status === 200) {
            const responseClone = networkResponse.clone();
            caches.open(CACHE_NAME).then(cache => {
              cache.put(event.request, responseClone);
            });
          }
          return networkResponse;
        });
      })
      .catch(() => {
        // Offline fallback — tidak ada fallback untuk navigasi
      })
  );
});
