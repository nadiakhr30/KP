/**
 * PWA Install Prompt Handler
 * Menampilkan tombol install saat browser mendukung PWA
 */

let deferredPrompt;
const installPromptEl = document.getElementById('pwa-install-prompt');
const installBtnEl = document.getElementById('pwa-install-btn');
const closeBtnEl = document.getElementById('pwa-close-btn');

// Tangkap event beforeinstallprompt
window.addEventListener('beforeinstallprompt', (e) => {
  // Cegah mini-infobar bawaan browser
  e.preventDefault();
  
  // Simpan event untuk nanti
  deferredPrompt = e;
  
  // Tampilkan tombol install custom
  if (installPromptEl) {
    installPromptEl.style.display = 'flex';
  }
});

// Handle klik tombol install
if (installBtnEl) {
  installBtnEl.addEventListener('click', async () => {
    if (!deferredPrompt) return;
    
    // Tampilkan prompt install bawaan
    deferredPrompt.prompt();
    
    // Tunggu user memilih
    const { outcome } = await deferredPrompt.userChoice;
    console.log(`User response to the install prompt: ${outcome}`);
    
    // Reset deferredPrompt
    deferredPrompt = null;
    
    // Sembunyikan tombol install custom
    if (installPromptEl) {
      installPromptEl.style.display = 'none';
    }
  });
}

// Handle klik tombol close
if (closeBtnEl) {
  closeBtnEl.addEventListener('click', () => {
    if (installPromptEl) {
      installPromptEl.style.display = 'none';
    }
    deferredPrompt = null;
  });
}

// Deteksi ketika app sudah diinstall
window.addEventListener('appinstalled', () => {
  console.log('PWA was installed');
  if (installPromptEl) {
    installPromptEl.style.display = 'none';
  }
  deferredPrompt = null;
});

// Deteksi jika app dijalankan dalam mode standalone (sebagai app installed)
if (window.navigator.standalone === true) {
  console.log('Running as a standalone PWA');
}
