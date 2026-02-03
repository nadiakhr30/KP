// Service Worker for Sistem Kehumasan PWA
const CACHE_NAME = 'sistem-kehumasan-v1';
const urlsToCache = [
  '/Sistem Kehumasan/KP/offline.html',
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

  event.respondWith(
    caches.match(event.request)
      .then((response) => {
        // Return cached response if available
        if (response) {
          return response;
        }

        // Otherwise fetch from network
        return fetch(event.request)
          .then((response) => {
            // Don't cache if not a successful response
            if (!response || response.status !== 200 || response.type !== 'basic') {
              return response;
            }

            // Clone the response
            const responseToCache = response.clone();

            // Cache the response for future use
            caches.open(CACHE_NAME)
              .then((cache) => {
                cache.put(event.request, responseToCache);
              });

            return response;
          })
          .catch(() => {
        // Return offline page or appropriate cached dashboard
        console.log('Service Worker: Network request failed, using offline fallback');
        
        // Try to serve appropriate dashboard based on request URL
        const requestUrl = new URL(event.request.url);
        
        if (requestUrl.pathname.includes('/pegawai/')) {
          return caches.match('/Sistem Kehumasan/KP/pegawai/index.php')
            .then(response => response || caches.match('/Sistem Kehumasan/KP/offline.html'));
        } else {
          return caches.match('/Sistem Kehumasan/KP/admin/index.php')
            .then(response => response || caches.match('/Sistem Kehumasan/KP/offline.html'));
        }
          });
      })
  );
});
