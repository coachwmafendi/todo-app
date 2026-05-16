<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

Route::get('/', [TodoController::class, 'index']);

Route::get('/manifest.json', function () {
    $sizes = [72, 96, 128, 144, 152, 192, 384, 512];

    $icons = collect($sizes)->map(fn ($size) => [
        'src' => asset("icons/icon-{$size}x{$size}.png"),
        'sizes' => "{$size}x{$size}",
        'type' => 'image/png',
    ]);

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
