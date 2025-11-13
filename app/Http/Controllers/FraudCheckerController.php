<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoreSetting;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class FraudCheckerController extends Controller
{
    public function index()
    {
        return Inertia::render('fraud/Index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'phone' => 'required'
        ]);

        $phone = trim($request->phone);

        // ✅ Fetch API key from DB and trim
        $apiKey = trim(StoreSetting::current()->fraudchecker_api_key ?? '');

        if (empty($apiKey)) {
            Log::error('❌ FraudChecker API key missing or empty in settings!');
            return response()->json(['error' => 'API Key missing or empty in settings!'], 500);
        }

        // ✅ FraudSpy official endpoint (correct one)
        $url = "https://fraudspy.com.bd/api/v1/search";

        // ✅ Must send JSON (the API expects JSON, not x-www-form-urlencoded)
        $payload = json_encode(['phone' => $phone]);

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                "Authorization: Bearer $apiKey",
                "Content-Type: application/json",
                "Accept: application/json",
            ],
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_SSL_VERIFYPEER => false, // ⚠️ disable only for local testing
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // ✅ Log response for debugging
        Log::info('FraudChecker API response', [
            'phone' => $phone,
            'http_code' => $httpCode,
            'response' => $response,
            'curl_error' => $curlError,
        ]);

        if ($response === false) {
            return response()->json(['error' => 'cURL Error: ' . $curlError], 500);
        }

        $data = json_decode($response, true);

        if ($data === null) {
            return response()->json([
                'error' => 'Invalid JSON response from FraudChecker',
                'raw'   => $response
            ], 500);
        }

        // ✅ Handle invalid API key
        if ($httpCode == 403) {
            return response()->json([
                'error' => 'API Key is invalid or expired. Please check your FraudChecker API key.'
            ], 403);
        }

        // ✅ Transform response to match frontend structure
        $result = [
            'ok' => $data['ok'] ?? true,
            'phone' => $data['phone'] ?? [],
            'overall' => [
                'total' => $data['overall']['total'] ?? ($data['total_parcels'] ?? 0),
                'delivered' => $data['overall']['delivered'] ?? ($data['total_delivered'] ?? 0),
                'returned' => $data['overall']['returned'] ?? ($data['total_cancel'] ?? 0),
                'success_ratio' => $data['overall']['success_ratio']
                    ?? ($data['total_parcels']
                        ? round(($data['total_delivered'] / $data['total_parcels']) * 100, 2)
                        : 0),
            ],
            'couriers' => collect($data['couriers'] ?? $data['apis'] ?? [])->map(function ($c) {
                return [
                    'total' => $c['total'] ?? ($c['total_parcels'] ?? 0),
                    'successful' => $c['successful'] ?? ($c['total_delivered_parcels'] ?? 0),
                    'returned' => $c['returned'] ?? ($c['total_cancelled_parcels'] ?? 0),
                ];
            })->toArray(),
        ];

        return response()->json($result, $httpCode);
    }
}
