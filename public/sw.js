const CACHE_NAME = 'mister-wang-v3';

const OFFLINE_URL = '/offline.html';

const ASSETS_TO_CACHE = [
    '/',
    OFFLINE_URL,
    '/css/styles.css',
    '/manifest.json',
    '/favicon.ico'
];

/* ---------------- INSTALL ---------------- */
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            return cache.addAll(ASSETS_TO_CACHE);
        })
    );
    self.skipWaiting();
});

/* ---------------- ACTIVATE ---------------- */
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys => {
            return Promise.all(
                keys
                    .filter(key => key !== CACHE_NAME)
                    .map(key => caches.delete(key))
            );
        })
    );
    self.clients.claim();
});

/* ---------------- FETCH ---------------- */
self.addEventListener('fetch', event => {

    if (event.request.method !== 'GET') {
        return;
    }

    const url = new URL(event.request.url);

    /* ❌ ADMIN + AUTH – NIKAD OFFLINE */
    if (
        url.pathname.startsWith('/admin') ||
        url.pathname.startsWith('/login') ||
        url.pathname.startsWith('/logout')
    ) {
        return;
    }

    /* ✅ STATIC ASSETS – CACHE FIRST */
    if (
        url.pathname.endsWith('.css') ||
        url.pathname.endsWith('.js') ||
        url.pathname.endsWith('.png') ||
        url.pathname.endsWith('.jpg') ||
        url.pathname.endsWith('.jpeg') ||
        url.pathname.endsWith('.webp') ||
        url.pathname.endsWith('.svg') ||
        url.pathname.endsWith('.ico')
    ) {
        event.respondWith(
            caches.match(event.request).then(cached => {
                return (
                    cached ||
                    fetch(event.request).then(response => {
                        const clone = response.clone();
                        caches.open(CACHE_NAME).then(cache => {
                            cache.put(event.request, clone);
                        });
                        return response;
                    })
                );
            })
        );
        return;
    }

    /* ✅ HTML – NETWORK FIRST + OFFLINE */
    event.respondWith(
        fetch(event.request)
            .then(response => {
                if (
                    response.status === 200 &&
                    event.request.headers.get('accept')?.includes('text/html')
                ) {
                    const clone = response.clone();
                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, clone);
                    });
                }
                return response;
            })
            .catch(() => {
                if (event.request.headers.get('accept')?.includes('text/html')) {
                    return caches.match(OFFLINE_URL);
                }

                // ⛑️ BITNO: uvek vrati Response
                return Response.error();
            })
    );
});
