importScripts('https://www.gstatic.com/firebasejs/12.7.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/12.7.0/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "AIzaSyC4Gciq0uhrD9GyGVzcCbs-yI7I-mJcnJc",
    authDomain: "mister-wang-7a3ba.firebaseapp.com",
    projectId: "mister-wang-7a3ba",
    storageBucket: "mister-wang-7a3ba.firebasestorage.app",
    messagingSenderId: "552659844430",
    appId: "1:552659844430:web:eaa3579224b0effc70f21f"
});

const messaging = firebase.messaging();

/**
 * 🔔 PUSH dok admin NIJE na sajtu
 */
messaging.onBackgroundMessage(function (payload) {
    console.log('[FCM] Background message:', payload);

    const title = payload.notification?.title || 'Nova porudžbina';
    const options = {
        body: payload.notification?.body || 'Stigla je nova porudžbina',
        icon: '/icons/icon-192.png',
        data: {
            url: payload.data?.url || '/admin/orders'
        }
    };

    self.registration.showNotification(title, options);
});

/**
 * 👉 KLIK NA NOTIFIKACIJU
 */
self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    const url = event.notification.data?.url || '/admin/orders';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(clientList => {
            for (const client of clientList) {
                if (client.url.includes(url) && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(url);
            }
        })
    );
});
