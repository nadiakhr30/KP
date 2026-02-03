// Service Worker for Sistem Kehumasan PWA
const CACHE_NAME = 'sistem-kehumasan-v1';
const urlsToCache = [
  '/Sistem Kehumasan/KP/offline.html',
  '/Sistem Kehumasan/KP/index.php',
  '/Sistem Kehumasan/KP/admin/index.php',
  '/Sistem Kehumasan/KP/admin/assets/css/style.css',
  '/Sistem Kehumasan/KP/admin/assets/css/custom.css',
  '/Sistem Kehumasan/KP/pegawai/index.php',
  '/Sistem Kehumasan/KP/pegawai/assets/css/style.css',
  '/Sistem Kehumasan/KP/pegawai/assets/css/custom.css',
  '/Sistem Kehumasan/KP/images/sikumbang.ico'
];

// Install event - cache essential files
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('Service Worker: Cache opened');
        return cache.addAll(urlsToCache).catch((err) => {
          console.log('Service Worker: Some files failed to cache', err);
          // Continue even if some files fail
        });
      })
  );
  self.skipWaiting();
});

// Activate event - clean old caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            console.log('Service Worker: Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  self.clients.claim();
});

// Fetch event - serve from cache, fallback to network
self.addEventListener('fetch', (event) => {
  // Skip non-GET requests
  if (event.request.method !== 'GET') {
    return;
  }
  const requestUrl = new URL(event.request.url);

  // Treat navigations and dashboard/index requests as network-first
  const isNavigation = event.request.mode === 'navigate' ||
    requestUrl.pathname.endsWith('/index.php') ||
    requestUrl.pathname.includes('/admin/') ||
    requestUrl.pathname.includes('/pegawai/') ||
    requestUrl.pathname === '/Sistem Kehumasan/KP/' ||
    requestUrl.pathname === '/Sistem Kehumasan/KP';

  if (isNavigation) {
    event.respondWith(
      fetch(event.request)
        .then((networkResponse) => {
          if (networkResponse && networkResponse.status === 200) {
            const copy = networkResponse.clone();
            caches.open(CACHE_NAME).then((cache) => {
              cache.put(event.request, copy);
            });
          }
          return networkResponse;
        })
        .catch(() => {
          // Network failed -> try cache -> then offline.html
          return caches.match(event.request)
            .then((cached) => cached || (
              requestUrl.pathname.includes('/pegawai/') ?
                caches.match('/Sistem Kehumasan/KP/pegawai/index.php') :
                caches.match('/Sistem Kehumasan/KP/admin/index.php')
            ))
            .then((resp) => resp || caches.match('/Sistem Kehumasan/KP/offline.html'));
        })
    );
    return;
  }

  // For other requests (static assets) use cache-first then network
  event.respondWith(
    caches.match(event.request).then((response) => {
      if (response) {
        return response;
      }
      return fetch(event.request)
        .then((networkResponse) => {
          if (!networkResponse || networkResponse.status !== 200) {
            return networkResponse;
          }
          const responseToCache = networkResponse.clone();
          caches.open(CACHE_NAME).then((cache) => cache.put(event.request, responseToCache));
          return networkResponse;
        })
        .catch(() => caches.match('/Sistem Kehumasan/KP/offline.html'));
    })
  );
});

// Allow the page to tell the SW to skipWaiting (for immediate activation)
self.addEventListener('message', (event) => {
  if (!event.data) return;
  if (event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});
