# PWA Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add PWA installability to the Funky Todo List app (manifest, service worker, meta tags, icons).

**Architecture:** Route-based manifest.json generation (for dynamic values), static service worker in public/, inline SW registration in welcome.blade.php head.

**Tech Stack:** Laravel 13, Livewire 4, PHP GD (icon generation)

---

### Task 1: Generate remaining app icons

**Files:**
- Create: `public/icons/icon-72x72.png`
- Create: `public/icons/icon-96x96.png`
- Create: `public/icons/icon-128x128.png`
- Create: `public/icons/icon-144x144.png`
- Create: `public/icons/icon-152x152.png`
- Create: `public/icons/icon-384x384.png`
- (Already exists: icon-192x192.png, icon-512x512.png)

- [ ] **Step 1: Generate all icon sizes via PHP GD**

Run:
```bash
php -r '
$sizes = [72, 96, 128, 144, 152, 192, 384, 512];
$bg = [49, 46, 129];
$accent = [250, 204, 21];
$white = [255, 255, 255];

foreach ($sizes as $size) {
    $im = imagecreatetruecolor($size, $size);
    imagealphablending($im, true);

    $bgColor = imagecolorallocate($im, $bg[0], $bg[1], $bg[2]);
    imagefilledrectangle($im, 0, 0, $size, $size, $bgColor);

    $borderW = max(3, intval($size * 0.05));
    $accentColor = imagecolorallocate($im, $accent[0], $accent[1], $accent[2]);
    imagefilledrectangle($im, 0, 0, $size, $borderW, $accentColor);
    imagefilledrectangle($im, 0, $size - $borderW, $size, $size, $accentColor);
    imagefilledrectangle($im, 0, 0, $borderW, $size, $accentColor);
    imagefilledrectangle($im, $size - $borderW, 0, $size, $size, $accentColor);

    $whiteColor = imagecolorallocate($im, 255, 255, 255);
    $thick = max(3, intval($size * 0.07));
    $margin = $size * 0.25;
    $p1x = intval($margin);
    $p1y = intval($size * 0.55);
    $p2x = intval($size * 0.43);
    $p2y = intval($size * 0.72);
    $p3x = intval($size - $margin);
    $p3y = intval($size * 0.35);

    $half = intval($thick / 2);
    for ($dx = -$half; $dx <= $half; $dx++) {
        for ($dy = -$half; $dy <= $half; $dy++) {
            if (abs($dx) + abs($dy) <= $half) {
                imageline($im, $p1x + $dx, $p1y + $dy, $p2x + $dx, $p2y + $dy, $whiteColor);
                imageline($im, $p2x + $dx, $p2y + $dy, $p3x + $dx, $p3y + $dy, $whiteColor);
            }
        }
    }

    imagepng($im, "public/icons/icon-{$size}x{$size}.png");
    imagedestroy($im);
    echo "Generated icon-{$size}x{$size}.png\n";
}
'
```

Expected: All 8 icon files created in `public/icons/`.

- [ ] **Step 2: Verify all icons exist**

Run: `ls -la public/icons/`
Expected: 8 files (72, 96, 128, 144, 152, 192, 384, 512)

---

### Task 2: Add manifest.json route

**Files:**
- Modify: `routes/web.php`

- [ ] **Step 1: Add manifest route to routes/web.php**

Read current `routes/web.php`, then add before or after the existing route:

```php
Route::get('/manifest.json', function () {
    $icons = [];
    $sizes = [72, 96, 128, 144, 152, 192, 384, 512];

    foreach ($sizes as $size) {
        $icons[] = [
            'src' => asset("icons/icon-{$size}x{$size}.png"),
            'sizes' => "{$size}x{$size}",
            'type' => 'image/png',
        ];
    }

    return response()->json([
        'name' => config('app.name'),
        'short_name' => 'Todos',
        'start_url' => '/',
        'display' => 'standalone',
        'background_color' => '#312e81',
        'theme_color' => '#facc15',
        'icons' => $icons,
    ]);
})->name('manifest.json');
```

---

### Task 3: Create service worker

**Files:**
- Create: `public/serviceworker.js`

- [ ] **Step 1: Write the service worker**

```javascript
var staticCacheName = 'pwa-v' + new Date().getTime();

var filesToCache = [
    '/icons/icon-72x72.png',
    '/icons/icon-96x96.png',
    '/icons/icon-128x128.png',
    '/icons/icon-144x144.png',
    '/icons/icon-152x152.png',
    '/icons/icon-192x192.png',
    '/icons/icon-384x384.png',
    '/icons/icon-512x512.png',
];

self.addEventListener('install', function (event) {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName).then(function (cache) {
            return cache.addAll(filesToCache);
        })
    );
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (cacheNames) {
            return Promise.all(
                cacheNames
                    .filter(function (cacheName) {
                        return cacheName.startsWith('pwa-') && cacheName !== staticCacheName;
                    })
                    .map(function (cacheName) {
                        return caches.delete(cacheName);
                    })
            );
        })
    );
});

self.addEventListener('fetch', function (event) {
    event.respondWith(
        caches.match(event.request).then(function (response) {
            return response || fetch(event.request);
        })
    );
});
```

---

### Task 4: Update welcome.blade.php with PWA meta tags

**Files:**
- Modify: `resources/views/welcome.blade.php`

- [ ] **Step 1: Read current welcome.blade.php**

Run: `cat resources/views/welcome.blade.php`

- [ ] **Step 2: Add PWA tags inside `<head>`**

Insert after the `<title>` tag:

```html
    <link rel="manifest" href="{{ route('manifest.json') }}">
    <meta name="theme-color" content="#facc15">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/serviceworker.js', { scope: '/' });
        }
    </script>
```

---

### Task 5: Smoke test the PWA

**Files:**
- Test: N/A (manual verification)

- [ ] **Step 1: Start the dev server**

Run: `php artisan serve &`

- [ ] **Step 2: Verify manifest.json loads**

Run: `curl -s http://127.0.0.1:8000/manifest.json | php -r 'echo json_encode(json_decode(file_get_contents("php://stdin")), JSON_PRETTY_PRINT);'`

Expected: Valid JSON with name, icons array, theme_color, etc.

- [ ] **Step 3: Verify serviceworker.js is accessible**

Run: `curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8000/serviceworker.js`

Expected: 200

- [ ] **Step 4: Verify icons are accessible**

Run: `curl -s -o /dev/null -w "%{http_code}" http://127.0.0.1:8000/icons/icon-192x192.png`

Expected: 200

- [ ] **Step 5: Stop the dev server**

Run: `kill %1 2>/dev/null; true`

---

### Task 6: Commit

**Files:**
- Commit all changes

- [ ] **Step 1: Stage and commit**

```bash
git add public/icons/ routes/web.php public/serviceworker.js resources/views/welcome.blade.php docs/superpowers/specs/2026-05-16-pwa-design.md docs/superpowers/plans/2026-05-16-pwa-implementation.md
git commit -m "feat: add PWA support with manifest, service worker, icons, and meta tags"
```
