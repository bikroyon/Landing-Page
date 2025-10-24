<?php

namespace App\Http\Controllers;

use App\Models\DeliveryZone;
use Illuminate\Http\Request;

class DeliveryZoneController extends Controller
{
    /**
     * Display a listing of the delivery zones.
     */
    public function index()
    {
        $zones = DeliveryZone::orderBy('name')->get();
        return view('admin.delivery_zones.index', compact('zones'));
    }

    /**
     * Show the form for creating a new delivery zone.
     */
    public function create()
    {
        return view('admin.delivery_zones.create');
    }

    /**
     * Store a newly created delivery zone in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'region' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'delivery_fee' => 'required|numeric|min:0',
            'free_delivery_min_order' => 'nullable|numeric|min:0',
            'status' => 'required|boolean',
        ]);

        DeliveryZone::create($validated);

        return redirect()->route('delivery-zones.index')
                         ->with('success', 'Delivery Zone created successfully.');
    }

    /**
     * Display the specified delivery zone.
     */
    public function show(DeliveryZone $deliveryZone)
    {
        return view('admin.delivery_zones.show', compact('deliveryZone'));
    }

    /**
     * Show the form for editing the specified delivery zone.
     */
    public function edit(DeliveryZone $deliveryZone)
    {
        return view('admin.delivery_zones.edit', compact('deliveryZone'));
    }

    /**
     * Update the specified delivery zone in storage.
     */
    public function update(Request $request, DeliveryZone $deliveryZone)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'region' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'delivery_fee' => 'required|numeric|min:0',
            'free_delivery_min_order' => 'nullable|numeric|min:0',
            'status' => 'required|boolean',
        ]);

        $deliveryZone->update($validated);

        return redirect()->route('delivery-zones.index')
                         ->with('success', 'Delivery Zone updated successfully.');
    }

    /**
     * Remove the specified delivery zone from storage.
     */
    public function destroy(DeliveryZone $deliveryZone)
    {
        $deliveryZone->delete();

        return redirect()->route('delivery-zones.index')
                         ->with('success', 'Delivery Zone deleted successfully.');
    }

    /**
     * Toggle the status of a delivery zone (active/inactive)
     */
    public function toggleStatus(DeliveryZone $deliveryZone)
    {
        $deliveryZone->status = !$deliveryZone->status;
        $deliveryZone->save();

        return redirect()->route('delivery-zones.index')
                         ->with('success', 'Delivery Zone status updated successfully.');
    }
}
