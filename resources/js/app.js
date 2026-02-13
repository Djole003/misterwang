import './bootstrap';
import Alpine from 'alpinejs';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

window.Alpine = Alpine;
Alpine.start();


// ===============================
// PWA SERVICE WORKER REGISTRACIJA
// ===============================

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(reg => {
                console.log('Service Worker registrovan:', reg.scope);
            })
            .catch(err => {
                console.error('Service Worker greška:', err);
            });
    });
}


// ===============================
// INSTALL BUTTON LOGIKA
// ===============================

let deferredPrompt;
const installBtn = document.getElementById('installBtn');

window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;

    if (installBtn) {
        installBtn.style.display = 'inline-block';
    }
});

if (installBtn) {
    installBtn.addEventListener('click', async () => {
        if (!deferredPrompt) return;

        deferredPrompt.prompt();
        const { outcome } = await deferredPrompt.userChoice;

        if (outcome === 'accepted') {
            console.log('Korisnik instalirao aplikaciju');
        }

        deferredPrompt = null;
        installBtn.style.display = 'none';
    });
}

window.addEventListener('appinstalled', () => {
    if (installBtn) {
        installBtn.style.display = 'none';
    }
});
