<?php

namespace App\Http\Controllers;

use App\Models\Tracking;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TrackingController extends Controller
{
    public function index()
    {
        $tracking = Tracking::first();
        return Inertia::render('tracking/Index', [
            'tracking' => $tracking,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'facebook_pixel_id' => 'nullable|string',
            'facebook_access_token' => 'nullable|string',
            'facebook_test_event' => 'boolean',
            'facebook_test_event_code' => 'nullable|string',
            'google_tag_manager_id' => 'nullable|string',
            'google_analytics_id' => 'nullable|string',
            'data_layer' => 'nullable|array',
        ]);

        $tracking = Tracking::first();

        if ($tracking) {
            $tracking->update($data);
        } else {
            Tracking::create($data);
        }

        return redirect()->back()->with('success', 'Tracking settings saved!');
    }
}
