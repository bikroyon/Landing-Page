<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class FacebookCAPI
{
    public static function sendEvent(array $eventData)
    {
        $store = \App\Models\StoreSetting::first();
        $pixelId = $store->fb_pixel_id;
        $accessToken = $store->fb_pixel_access_token;

        if (!$pixelId || !$accessToken) return null;

        $url = "https://graph.facebook.com/v17.0/{$pixelId}/events?access_token={$accessToken}";

        $response = Http::post($url, [
            'data' => [$eventData]
        ]);

        return $response->json();
    }
}
