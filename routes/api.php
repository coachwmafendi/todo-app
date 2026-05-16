<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SyncController;
use App\Http\Controllers\Api\DeviceController;

Route::post('/sync', [SyncController::class, 'push']);
Route::get('/sync', [SyncController::class, 'pull']);

Route::post('/devices/register', [DeviceController::class, 'register']);
Route::post('/devices/deregister', [DeviceController::class, 'deregister']);