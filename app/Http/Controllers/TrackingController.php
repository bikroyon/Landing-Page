<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tracking;

class TrackingController extends Controller
{
    // List all tracking records
    public function index()
    {
        $trackings = Tracking::latest()->paginate(15);
        return response()->json($trackings);
    }

    // Store new tracking record
    public function store(Request $request)
    {
        $tracking = Tracking::create([
            'session_id' => $request->session()->getId(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'fb_pixel_id' => $request->fb_pixel_id,
            'google_analytics_id' => $request->google_analytics_id,
            'event_name' => $request->event_name,
            'event_data' => $request->event_data,
        ]);

        return response()->json(['success' => true, 'tracking' => $tracking]);
    }

    // Update an existing tracking record
    public function update(Request $request, Tracking $tracking)
    {
        $tracking->update([
            'fb_pixel_id' => $request->fb_pixel_id,
            'google_analytics_id' => $request->google_analytics_id,
            'event_name' => $request->event_name,
            'event_data' => $request->event_data,
        ]);

        return response()->json(['success' => true, 'tracking' => $tracking]);
    }

    // Delete a tracking record
    public function destroy(Tracking $tracking)
    {
        $tracking->delete();
        return response()->json(['success' => true]);
    }
}
