// Service Worker for Filament Admin Panel PWA
const CACHE_NAME = '{{ $swConfig['cache_name'] }}';
const OFFLINE_URL = '{{ $swConfig['offline_url'] }}';

// Assets to cache for offline functionality
const STATIC_CACHE_URLS = @json($swConfig['cache_urls']);

// Dynamic cache patterns
const CACHE_PATTERNS = {
@foreach($swConfig['cache_patterns'] as $name => $pattern)
  {{ $name }}: new RegExp('{!! $pattern !!}'),
@endforeach
};

// Install event - cache static assets
self.addEventListener('install', event => {
  console.log('[SW] Installing service worker...');
  
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('[SW] Caching static assets');
        return cache.addAll(STATIC_CACHE_URLS);
      })
      .then(() => {
        console.log('[SW] Static assets cached successfully');
        return self.skipWaiting();
      })
      .catch(error => {
        console.error('[SW] Failed to cache static assets:', error);
      })
  );
});

// Activate event - clean up old caches
self.addEventListener('activate', event => {
  console.log('[SW] Activating service worker...');
  
  event.waitUntil(
    caches.keys()
      .then(cacheNames => {
        return Promise.all(
          cacheNames.map(cacheName => {
            if (cacheName !== CACHE_NAME) {
              console.log('[SW] Deleting old cache:', cacheName);
              return caches.delete(cacheName);
            }
          })
        );
      })
      .then(() => {
        console.log('[SW] Service worker activated');
        return self.clients.claim();
      })
  );
});

// Fetch event - handle requests
self.addEventListener('fetch', event => {
  const url = new URL(event.request.url);
  
  // Skip non-GET requests
  if (event.request.method !== 'GET') {
    return;
  }
  
  // Skip cross-origin requests
  if (url.origin !== location.origin) {
    return;
  }

  // Handle different types of requests
  if (url.pathname.startsWith('/admin')) {
    event.respondWith(handleAdminRequest(event.request));
  } else if (CACHE_PATTERNS.filament_assets.test(url.pathname)) {
    event.respondWith(handleAssetRequest(event.request));
  } else if (CACHE_PATTERNS.images.test(url.pathname)) {
    event.respondWith(handleImageRequest(event.request));
  } else if (CACHE_PATTERNS.fonts.test(url.pathname)) {
    event.respondWith(handleFontRequest(event.request));
  }
});

// Handle admin panel requests (Network First strategy)
async function handleAdminRequest(request) {
  try {
    const networkResponse = await fetch(request);
    
    if (networkResponse.ok) {
      const cache = await caches.open(CACHE_NAME);
      cache.put(request, networkResponse.clone());
    }
    
    return networkResponse;
  } catch (error) {
    console.log('[SW] Network failed for admin request:', request.url);
    
    const cachedResponse = await caches.match(request);
    if (cachedResponse) {
      return cachedResponse;
    }
    
    // If no cache, return offline page for navigation requests
    if (request.mode === 'navigate') {
      return caches.match(OFFLINE_URL) || new Response(
        getOfflineHTML(),
        { headers: { 'Content-Type': 'text/html' } }
      );
    }
    
    throw error;
  }
}

// Handle asset requests (Cache First strategy)
async function handleAssetRequest(request) {
  const cachedResponse = await caches.match(request);
  
  if (cachedResponse) {
    return cachedResponse;
  }
  
  try {
    const networkResponse = await fetch(request);
    
    if (networkResponse.ok) {
      const cache = await caches.open(CACHE_NAME);
      cache.put(request, networkResponse.clone());
    }
    
    return networkResponse;
  } catch (error) {
    console.log('[SW] Failed to fetch asset:', request.url);
    throw error;
  }
}

// Handle image requests (Cache First strategy)
async function handleImageRequest(request) {
  const cachedResponse = await caches.match(request);
  
  if (cachedResponse) {
    return cachedResponse;
  }
  
  try {
    const networkResponse = await fetch(request);
    
    if (networkResponse.ok) {
      const cache = await caches.open(CACHE_NAME);
      cache.put(request, networkResponse.clone());
    }
    
    return networkResponse;
  } catch (error) {
    console.log('[SW] Failed to fetch image:', request.url);
    // Return a placeholder image or throw error
    throw error;
  }
}

// Handle font requests (Cache First strategy)
async function handleFontRequest(request) {
  const cachedResponse = await caches.match(request);
  
  if (cachedResponse) {
    return cachedResponse;
  }
  
  try {
    const networkResponse = await fetch(request);
    
    if (networkResponse.ok) {
      const cache = await caches.open(CACHE_NAME);
      cache.put(request, networkResponse.clone());
    }
    
    return networkResponse;
  } catch (error) {
    console.log('[SW] Failed to fetch font:', request.url);
    throw error;
  }
}

// Generate offline HTML fallback with Tailwind CSS and RTL/LTR support
function getOfflineHTML() {
  return `
    <!DOCTYPE html>
    <html lang="en" dir="ltr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Offline - Admin Panel</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            primary: '{{ config('filament-pwa.theme_color', '#A77B56') }}'
                        }
                    }
                }
            }
        </script>
        <style>
            .retry-btn {
                background: {{ config('filament-pwa.theme_color', '#A77B56') }};
            }
            .retry-btn:hover {
                background: {{ config('filament-pwa.theme_color', '#A77B56') }}dd;
            }
        </style>
    </head>
    <body class="font-sans m-0 p-8 bg-gray-50 text-gray-700 text-center min-h-screen flex items-center justify-center flex-col">
        <div class="max-w-sm bg-white p-8 rounded-2xl shadow-lg">
            <div class="text-6xl mb-4">📱</div>
            <h1 class="m-0 mb-4 text-gray-800 text-xl font-semibold">You're Offline</h1>
            <p class="m-0 mb-6 leading-relaxed text-gray-600">It looks like you've lost your internet connection. Don't worry, you can still access some features of the admin panel.</p>
            <button class="retry-btn text-white border-none px-6 py-3 rounded-lg cursor-pointer text-base transition-colors duration-200 hover:opacity-90" onclick="window.location.reload()">
                Try Again
            </button>
        </div>
    </body>
    </html>
  `;
}

// Background sync for form submissions (if needed)
self.addEventListener('sync', event => {
  if (event.tag === 'background-sync') {
    console.log('[SW] Background sync triggered');
    event.waitUntil(doBackgroundSync());
  }
});

async function doBackgroundSync() {
  // Implement background sync logic here
  console.log('[SW] Performing background sync...');
}

// Push notification handling (if needed)
self.addEventListener('push', event => {
  console.log('[SW] Push notification received');
  
  const options = {
    body: event.data ? event.data.text() : 'New notification',
    icon: '/images/icons/icon-192x192.png',
    badge: '/images/icons/icon-96x96.png',
    vibrate: [100, 50, 100],
    data: {
      dateOfArrival: Date.now(),
      primaryKey: 1
    }
  };
  
  event.waitUntil(
    self.registration.showNotification('Admin Panel', options)
  );
});

// Notification click handling
self.addEventListener('notificationclick', event => {
  console.log('[SW] Notification clicked');
  
  event.notification.close();
  
  event.waitUntil(
    clients.openWindow('/admin')
  );
});
