# Panduan PWA (Progressive Web App) - Sikumbang

## Deskripsi
Sikumbang adalah PWA yang dapat diinstall di desktop, tablet, dan smartphone. Setelah diinstall, user dapat mengakses aplikasi langsung dari beranda (home screen) tanpa perlu membuka browser.

## Fitur PWA
✅ **Installable** - Bisa diinstall di home screen/desktop
✅ **Offline Support** - Bekerja offline dengan caching strategy
✅ **Responsive** - Optimal di semua ukuran layar
✅ **Fast Loading** - Service Worker untuk caching aset
✅ **App Icon** - Icon custom untuk beranda

## Persyaratan PWA (Chrome/Edge/Firefox)
Untuk PWA bisa diinstall, harus memenuhi:
- ✅ HTTPS (atau localhost untuk development)
- ✅ manifest.json valid
- ✅ Service Worker terdaftar
- ✅ Icon 192x192 dan 512x512 px
- ✅ Display mode "standalone"

## File-file PWA

### 1. manifest.json
```
Location: /KP/manifest.json
Fungsi: Metadata aplikasi PWA
Isi:
- name, short_name
- icons (192x192 & 512x512)
- start_url, scope
- display: standalone
- theme_color, background_color
- shortcuts (admin & pegawai dashboard)
```

### 2. service-worker.js
```
Location: /KP/service-worker.js
Fungsi: Caching dan offline support
Strategi:
- Install: Cache files essensial
- Fetch: Network-first untuk pages, cache-first untuk assets
- Activate: Hapus cache lama
```

### 3. pwa-install.js
```
Location: /KP/assets/js/pwa-install.js
Fungsi: Handle install prompt & SW registration
- Menangkap beforeinstallprompt event
- Register Service Worker
- Support fallback untuk iOS
- Auto-update check setiap 1 jam
```

### 4. Icons
```
Location: /KP/assets/icons/
Files:
- icon-192x192.png (untuk home screen)
- icon-512x512.png (untuk splash screen)
- icon-maskable-192x192.png (adaptive icons)
- icon-maskable-512x512.png (adaptive icons)
```

## Cara Mengaktifkan PWA

### Desktop (Chrome/Edge)
1. Buka http://localhost/KP (atau domain Anda)
2. Tunggu sampai icon install muncul di address bar (⇩)
3. Klik icon install
4. Pilih "Install" di modal yang muncul
5. App akan muncul di Start Menu / Applications

### Mobile Android (Chrome/Samsung)
1. Buka https://domain.com/KP (harus HTTPS!)
2. Tap "Install App" button (custom prompt)
3. Tap "Install" di modal browser
4. App akan ditambahkan ke home screen

### iPhone/iPad (Safari)
Karena iOS tidak fully support beforeinstallprompt:
1. Buka Safari
2. Tap Share button (⬆️ icon)
3. Scroll dan pilih "Add to Home Screen"
4. Tap "Add"

## Testing Checklist

### Local Testing (Localhost)
```bash
# 1. Buka DevTools (F12)
# 2. Masuk ke Application tab
# 3. Cek manifest.json status
# 4. Cek Service Worker registered
# 5. Cek Cache Storage punya entries
# 6. Test offline mode: Network tab → Offline
```

### Production (HTTPS)
- Pastikan site punya HTTPS valid
- Lighthouse audit harus "PWA: 90+"
- Service Worker status: "Active and running"
- Install prompt harus muncul di address bar

## Mengupdate PWA

### Update App Version
1. Update manifest.json version jika ada
2. Update service-worker.js CACHE_NAME
```javascript
const CACHE_NAME = 'sistem-kehumasan-v2'; // increment version
```
3. Update file-file app
4. Service Worker akan otomatis detect update

### Force Update
Users yang sudah install:
- Auto-update cek setiap 1 jam (pwa-install.js)
- Atau manual: buka app → menu → "Update"

## Troubleshooting

### Install button tidak muncul
**Masalah:** beforeinstallprompt tidak triggered
**Solusi:**
- Pastikan HTTPS (production) atau localhost (dev)
- Cek manifest.json valid
- Cek di DevTools → Application → Manifest
- Pastikan icons ada dan accessible

### Service Worker tidak register
**Masalah:** SW registration gagal
**Solusi:**
```javascript
// Check console untuk error
// DevTools → Console → cari "Service Worker"
// Pastikan service-worker.js ada di root /KP/
```

### App tidak bisa offline
**Masalah:** Offline mode tidak berfungsi
**Solusi:**
- Pastikan offline.html ada
- Cek CACHE_NAME match di service-worker.js
- Clear cache: DevTools → Application → Clear storage

### Icons tidak muncul
**Masalah:** Icon blurry atau tidak ada
**Solusi:**
- Pastikan icons ada di /assets/icons/
- File names sesuai: icon-192x192.png, icon-512x512.png
- Format harus PNG dengan background
- Ukuran sesuai: exactly 192x192 dan 512x512

## Best Practices

### Caching Strategy
✅ **DO:**
- Cache static assets (CSS, JS, icons)
- Network-first untuk pages dinamis
- Update cache setelah fetch berhasil

❌ **DON'T:**
- Cache database queries langsung
- Cache session/auth tokens
- Cache user-specific data

### Manifest.json
✅ **DO:**
- Gunakan relative paths
- Icon harus 192x192 minimum
- Deskripsi singkat dan meaningful
- start_url mengarah ke login/home

❌ **DON'T:**
- Hardcode absolute paths
- Icon < 192x192
- Deskripsi terlalu panjang
- start_url mengarah ke halaman tertutup

### Performance
✅ **DO:**
- Minimize manifest.json
- Compress images
- Use efficient caching
- Monitor cache size

❌ **DON'T:**
- Cache terlalu banyak file
- Cache file besar (> 5MB)
- Nested caching strategy
- Block user interaction saat SW update

## Development Testing

### Simulate Offline
```
DevTools → Network tab → Throttling → Offline
Atau: DevTools → Application → Service Worker → Offline
```

### Clear Cache
```
DevTools → Application → Storage → Clear site data
Atau: Manual delete cache di Chrome settings
```

### Inspect Manifest
```
DevTools → Application → Manifest
Check valid format, icons, start_url
```

### Check Service Worker
```
DevTools → Application → Service Workers
Check: registered, active, update status
```

## Links & Resources

- [PWA Docs](https://developer.mozilla.org/en-US/docs/Web/Progressive_web_apps)
- [Web App Manifest](https://w3c.github.io/manifest/)
- [Service Workers](https://www.w3.org/TR/service-workers/)
- [Chrome DevTools PWA](https://developer.chrome.com/docs/devtools/progressive-web-apps/)

## Support & Issues

Untuk masalah atau pertanyaan:
1. Check console error (F12 → Console)
2. Check Service Worker status
3. Check manifest.json di DevTools
4. Clear cache dan reload
5. Test di mode incognito/private
