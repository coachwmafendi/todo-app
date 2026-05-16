# PWA (Progressive Web App) — Funky Todo List

## Overview

Add PWA support to the Funky Todo List app for installability on mobile and desktop. The app is a single-page Livewire todo list — the PWA provides a manifest, service worker for asset caching, and appropriate meta tags so browsers prompt users to "Add to Home Screen."

Offline data is **not** a requirement — the PWA covers installability and asset caching only.

## Files & Structure

| File | Purpose |
|---|---|
| `routes/web.php` | Serve `manifest.json` from a route |
| `public/serviceworker.js` | Static service worker — cache icons, CSS, JS; serve from cache |
| `resources/views/welcome.blade.php` | Add meta tags, manifest link, SW registration |

No new controllers, models, or packages. Zero dependencies.

## Manifest (`GET /manifest.json`)

- Served as a JSON response from a route in `routes/web.php` so dynamic values (name, icons paths via `asset()`) are available.
- **name**: "Funky Todo List"
- **short_name**: "Todos"
- **start_url**: `/`
- **display**: `standalone`
- **background_color**: `#312e81` (indigo-900)
- **theme_color**: `#facc15` (yellow-400, matches Neo-brutalism border accent)
- **icons**: 72x72, 96x96, 128x128, 144x144, 152x152, 192x192, 384x384, 512x512

## Service Worker (`public/serviceworker.js`)

Standard cache-first strategy:

- **install**: Cache app icons into a versioned cache (`pwa-v{timestamp}`)
- **activate**: Delete old caches (any starting with `pwa-` not matching current version)
- **fetch**: Serve from cache, fall back to network

## Layout Updates (`welcome.blade.php`)

Add inside `<head>`:

- `<link rel="manifest" href="/manifest.json">`
- `<meta name="theme-color" content="#facc15">`
- `<meta name="mobile-web-app-capable" content="yes">`
- `<meta name="apple-mobile-web-app-capable" content="yes">`
- `<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">`
- `<link rel="apple-touch-icon" href="/icons/icon-192x192.png">`
- Inline script to register the service worker

## Icons

All icons: indigo-900 background, yellow-400 thick border, white checkmark. Generated via PHP GD (no external tools needed).

Sizes: 72x72, 96x96, 128x128, 144x144, 152x152, 192x192, 384x384, 512x512.

## Non-Goals

- Offline task data caching
- Push notifications
- Background sync
- Any new database tables or models
