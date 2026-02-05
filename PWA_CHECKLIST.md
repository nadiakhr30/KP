# ✅ PWA Checklist - Sikumbang

## Verifikasi Setup PWA

### File & Struktur
- [x] `/manifest.json` - Terdapat & valid
- [x] `/service-worker.js` - Terdapat & configured
- [x] `/assets/js/pwa-install.js` - Terdapat & updated
- [x] `/offline.html` - Terdapat untuk offline fallback
- [x] `/assets/icons/icon-192x192.png` - Ada
- [x] `/assets/icons/icon-512x512.png` - Ada
- [x] `/assets/icons/icon-maskable-192x192.png` - Ada (maskable)
- [x] `/assets/icons/icon-maskable-512x512.png` - Ada (maskable)

### HTML Meta Tags
- [x] `index.php` - Punya manifest link
  ```html
  <link rel="manifest" href="manifest.json">
  ```
- [x] `index.php` - Punya apple-touch-icon
  ```html
  <link rel="apple-touch-icon" href="assets/icons/icon-192x192.png">
  ```
- [x] `index.php` - Punya theme-color meta
  ```html
  <meta name="theme-color" content="#343bb9">
  ```
- [x] `index.php` - Punya mobile-web-app-capable
  ```html
  <meta name="mobile-web-app-capable" content="yes">
  ```
- [x] `admin/layout.php` - Punya PWA install button
- [x] `admin/layout.php` - Punya manifest link
- [x] `pegawai/layout.php` - Punya manifest link (kalau ada)

### manifest.json Validation
- [x] `name` - Definite ✓
  ```json
  "name": "Sikumbang - Sistem Manajemen Kehumasan BPS Bangkalan"
  ```
- [x] `short_name` - Definite ✓
  ```json
  "short_name": "Sikumbang"
  ```
- [x] `icons` - Minimal 2 icons (192x192, 512x512)
  ```json
  "icons": [
    {"src": "assets/icons/icon-192x192.png", "sizes": "192x192"},
    {"src": "assets/icons/icon-512x512.png", "sizes": "512x512"},
    {"src": "assets/icons/icon-maskable-192x192.png", "sizes": "192x192", "purpose": "maskable"},
    {"src": "assets/icons/icon-maskable-512x512.png", "sizes": "512x512", "purpose": "maskable"}
  ]
  ```
- [x] `start_url` - Relative path
  ```json
  "start_url": "./index.php"
  ```
- [x] `scope` - Relative path
  ```json
  "scope": "./"
  ```
- [x] `display` - "standalone"
  ```json
  "display": "standalone"
  ```
- [x] `theme_color` - Definite
  ```json
  "theme_color": "#343bb9"
  ```
- [x] `background_color` - Definite
  ```json
  "background_color": "#ffffff"
  ```

### Service Worker
- [x] Registered di index.php
  ```javascript
  navigator.serviceWorker.register('./service-worker.js')
  ```
- [x] Fetch event handler
- [x] Cache strategy (network-first untuk pages, cache-first untuk assets)
- [x] Offline fallback (offline.html)
- [x] Cache versioning
  ```javascript
  const CACHE_NAME = 'sistem-kehumasan-v2';
  ```

### PWA Install Handler (pwa-install.js)
- [x] Listen to `beforeinstallprompt` event
- [x] Show custom install button
- [x] Handle install prompt
- [x] Detect `appinstalled` event
- [x] Fallback untuk iOS
- [x] Service Worker registration
- [x] Auto-update check

### Install Button UI
- [x] `index.php` (login page)
  - Location: Fixed bottom-right
  - Style: Gradient background + animation
  - Visibility: Hidden by default, shown on beforeinstallprompt
- [x] `admin/layout.php` (header)
  - Location: Header nav
  - Style: Consistent dengan design
  - Visibility: Hidden by default

### Testing Checklist

#### Local Development (http://localhost)
- [ ] DevTools → Application → Manifest
  - [ ] Status: Valid
  - [ ] All icons load
  - [ ] start_url correct
  
- [ ] DevTools → Application → Service Workers
  - [ ] Status: Active and running
  - [ ] Scope: ./
  
- [ ] DevTools → Application → Cache Storage
  - [ ] Cache ada entries
  - [ ] Versi cache correct
  
- [ ] DevTools → Network → Throttle to Offline
  - [ ] Offline page loading
  - [ ] Cached assets loaded
  - [ ] No blank pages
  
- [ ] Console
  - [ ] No error messages
  - [ ] SW registered log visible
  - [ ] Cache opened log visible

#### Production (https://domain.com)
- [ ] HTTPS enabled & valid certificate
- [ ] Manifest accessible dan valid
- [ ] Icons load correctly
- [ ] Service Worker installs
- [ ] Lighthouse audit:
  - [ ] PWA score >= 90
  - [ ] Performance >= 80
  - [ ] Accessibility >= 90
  - [ ] Best Practices >= 90

#### Installation Testing
- [ ] Chrome Desktop
  - [ ] Icon muncul di address bar
  - [ ] Install button berfungsi
  - [ ] App muncul di Start Menu
  
- [ ] Chrome Mobile (Android)
  - [ ] Custom install button muncul
  - [ ] Tap install berfungsi
  - [ ] Icon muncul di home screen
  
- [ ] Edge Desktop
  - [ ] Install button visible
  - [ ] App installable
  
- [ ] Safari (iOS)
  - [ ] Share button ada
  - [ ] "Add to Home Screen" visible
  - [ ] App installable

### Performance Checks

**Page Load Time**
- [ ] First Contentful Paint (FCP) < 2s
- [ ] Largest Contentful Paint (LCP) < 3s
- [ ] Time to Interactive (TTI) < 4s

**Cache Strategy**
- [ ] Static assets cache-first
- [ ] Pages network-first
- [ ] API responses cache dengan TTL
- [ ] Cache size < 50MB

**Offline Support**
- [ ] Can navigate cached pages
- [ ] Can fill forms (locally)
- [ ] Can submit forms (queue when online)
- [ ] Graceful degradation

### Security Checks

- [x] HTTPS required (production)
- [x] manifest.json tidak expose sensitive data
- [x] Service Worker tidak cache auth tokens
- [x] Icons dari trusted source
- [x] No mixed content (HTTP in HTTPS)

### Browser Support

| Browser | Version | Support |
|---------|---------|---------|
| Chrome | 58+ | ✅ Full support |
| Edge | 15+ | ✅ Full support |
| Firefox | 55+ | ✅ Full support |
| Safari | 11.1+ | ⚠️ Limited (no SW, only manifest) |
| Opera | 45+ | ✅ Full support |
| Samsung Internet | 8+ | ✅ Full support |

---

## Deployment Checklist

### Before Deploy
- [ ] Test di staging dengan HTTPS
- [ ] Run Lighthouse audit
- [ ] Test offline mode
- [ ] Test install on all devices
- [ ] Check console for errors
- [ ] Clear all caches
- [ ] Verify all assets load

### At Deployment
- [ ] Push latest code
- [ ] Clear CDN cache
- [ ] Verify service-worker.js served correctly
- [ ] Check manifest.json accessible
- [ ] Monitor error logs

### Post Deployment
- [ ] Test install on real devices
- [ ] Monitor PWA metrics
- [ ] Check user feedback
- [ ] Monitor cache hit rate
- [ ] Monitor offline usage

---

## Update Process

### For New Versions
1. Update `service-worker.js`:
   ```javascript
   const CACHE_NAME = 'sistem-kehumasan-v3'; // increment version
   ```

2. Update any file changes

3. Deploy to production

4. Service Worker will:
   - Detect update automatically
   - Install new version
   - Clean old caches
   - Users notified on next visit

### User Notification
- Auto-update check setiap 1 jam
- Manual refresh untuk apply update
- Or close & reopen app

---

## Monitoring

### Key Metrics
- PWA install rate (% of visitors who install)
- Active installed users
- Offline usage percentage
- Cache hit rate
- Update adoption rate

### Tools
- Google Analytics (PWA events)
- Lighthouse CI
- DevTools Console (errors)
- Performance monitoring
- Crash reporting

---

## Troubleshooting Commands

### Clear Everything
```javascript
// Run in console:
navigator.serviceWorker.getRegistrations().then(r => r.forEach(i => i.unregister()));
caches.keys().then(keys => keys.forEach(key => caches.delete(key)));
localStorage.clear();
```

### Force Update
```javascript
// In pwa-install.js:
navigator.serviceWorker.getRegistrations().then(registrations => {
  registrations.forEach(reg => reg.update());
});
```

### Check Service Worker
```javascript
// Run in console:
navigator.serviceWorker.getRegistrations().then(r => {
  r.forEach(reg => console.log(reg));
});
```

---

## References

- [PWA Checklist](https://developers.google.com/web/progressive-web-apps/checklist)
- [Manifest Spec](https://w3c.github.io/manifest/)
- [Service Worker Spec](https://www.w3.org/TR/service-workers/)
- [Lighthouse PWA Audit](https://developer.chrome.com/docs/lighthouse/pwa/)

---

**Last Updated:** February 2026
**Status:** ✅ Production Ready
