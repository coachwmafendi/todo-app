<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DeviceController
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'device_uuid' => 'required|string|uuid',
            'push_token' => 'required|string',
        ]);

        Cache::put("device:{$validated['device_uuid']}", $validated['push_token'], now()->addYear());

        return response()->json(['status' => 'registered']);
    }

    public function deregister(Request $request)
    {
        $validated = $request->validate([
            'device_uuid' => 'required|string|uuid',
        ]);

        Cache::forget("device:{$validated['device_uuid']}");

        return response()->json(['status' => 'deregistered']);
    }
}