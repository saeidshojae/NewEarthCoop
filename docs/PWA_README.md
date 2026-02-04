PWA Implementation notes
------------------------

Files added/changed:

- `public/sw.js` : service worker (precache, runtime caching, offline fallback)
- `public/offline.html` : navigation fallback page when offline
- `public/manifest.json` : updated to reference `/icons/icon-192.png` and `/icons/icon-512.png`
- `public/icons/` : folder to place PNG icons (add actual PNG files before deploy)
- `resources/js/app.js` : registers service worker and logs update events
- `resources/views/layouts/app.blade.php` : adds `theme-color` and `apple-touch-icon`

Local testing:

1. Build front-end assets:

```bash
npm install
npm run build
```

2. Serve Laravel and expose via HTTPS for PWA testing (mkcert + Caddy recommended) so the site is served over HTTPS. Alternatively use an HTTPS staging host.

3. Open site in Chrome on Android or desktop, check DevTools > Application > Manifest and Service Workers, then try Add to Home screen and Offline behavior.

Cache/version upgrade:

When deploying new assets, update cache names in `public/sw.js` (PRECACHE/RUNTIME) to a new version (e.g., v2) so clients will fetch updated assets and old caches will be cleaned up on activate.
