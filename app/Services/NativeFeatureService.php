<?php

namespace App\Services;

class NativeFeatureService
{
    public function capturePhoto(): ?string
    {
        try {
            $file = \Native\Camera::capture();
            return $file?->getPath();
        } catch (\Exception $e) {
            \Log::error('Camera capture failed: ' . $e->getMessage());
            return null;
        }
    }

    public function getCurrentLocation(): ?array
    {
        try {
            $location = \Native\Geolocation::get();
            if ($location) {
                return [
                    'lat' => $location->latitude,
                    'lng' => $location->longitude,
                ];
            }
            return null;
        } catch (\Exception $e) {
            \Log::error('Geolocation failed: ' . $e->getMessage());
            return null;
        }
    }

    public function registerPushNotifications(): ?string
    {
        try {
            return \Native\Firebase::getToken();
        } catch (\Exception $e) {
            \Log::error('Firebase token failed: ' . $e->getMessage());
            return null;
        }
    }
}