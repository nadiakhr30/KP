/**
 * PWA Install Handler - Notification/Toast Version
 * Menampilkan notification install otomatis
 * Klik Install → langsung install ke home screen/beranda
 */

let deferredPrompt;
let notificationShown = false;

console.log('📦 PWA Install Script Loaded');

// Function untuk tampilkan notification
function showInstallNotification() {
  if (notificationShown) return;
  
  const notif = document.getElementById('pwa-install-notification');
  if (notif) {
    notif.style.display = 'block';
    notificationShown = true;
    console.log('✅ Install notification ditampilkan');
  }
}

// Function untuk sembunyikan notification
function hideInstallNotification() {
  const notif = document.getElementById('pwa-install-notification');
  if (notif) {
    notif.style.display = 'none';
  }
}

// Tangkap event beforeinstallprompt
window.addEventListener('beforeinstallprompt', (e) => {
  console.log('✅ beforeinstallprompt event fired - PWA installable');
  e.preventDefault();
  deferredPrompt = e;
  
  // Tampilkan notification setelah 1 detik
  setTimeout(() => {
    showInstallNotification();
  }, 1000);
});

// EVENT DELEGATION: Handle install button click
document.addEventListener('click', async (e) => {
  const target = e.target;
  
  // Check apakah yang diklik adalah install button (support nested elements)
  const isInstallBtn = 
    target.id === 'pwa-install-btn' || 
    target.closest('#pwa-install-btn');
  
  // Check apakah yang diklik close button
  const isCloseBtn =
    target.id === 'pwa-notif-close-btn' ||
    target.closest('#pwa-notif-close-btn');
  
  // Handle install button click
  if (isInstallBtn) {
    console.log('🔘 Install button clicked');
    e.preventDefault();
    e.stopPropagation();
    
    if (!deferredPrompt) {
      console.log('❌ beforeinstallprompt tidak available');
      return;
    }
    
    try {
      console.log('📲 Triggering install prompt...');
      deferredPrompt.prompt();
      
      const { outcome } = await deferredPrompt.userChoice;
      console.log(`✅ User response: ${outcome}`);
      
      if (outcome === 'accepted') {
        console.log('🎉 PWA berhasil diinstall!');
        hideInstallNotification();
      } else {
        console.log('ℹ️ User membatalkan install');
      }
    } catch (err) {
      console.error('❌ Error during install:', err);
    }
    
    deferredPrompt = null;
  }
  
  // Handle close button click
  if (isCloseBtn) {
    console.log('❌ Close button clicked');
    e.preventDefault();
    e.stopPropagation();
    hideInstallNotification();
    notificationShown = false;
  }
});

// Deteksi ketika app sudah diinstall
window.addEventListener('appinstalled', () => {
  console.log('🎉 PWA successfully installed!');
  hideInstallNotification();
  deferredPrompt = null;
});

// Deteksi jika app dijalankan dari home screen
if (window.navigator.standalone === true) {
  console.log('✅ App running in standalone mode (dari home screen)');
}

// Register Service Worker untuk PWA
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
    navigator.serviceWorker.register('./service-worker.js', { scope: './' })
      .then(function(registration) {
        console.log('✅ Service Worker registered:', registration);
        
        // Check for updates setiap jam
        setInterval(function() {
          registration.update();
        }, 60 * 60 * 1000);
      })
      .catch(function(err) {
        console.log('ℹ️ Service Worker info:', err.message);
      });
  });
}
