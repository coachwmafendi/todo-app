<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funky Todo List ⚡</title>
    <link rel="manifest" href="{{ route('manifest.json') }}">
    <meta name="theme-color" content="#facc15">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/serviceworker.js', { scope: '/' });
        }
    </script>
</head>
<body class="bg-indigo-900">
    <livewire:todo-list />
    @livewireScripts
</body>
</html>
