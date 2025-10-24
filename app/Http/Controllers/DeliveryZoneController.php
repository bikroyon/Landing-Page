<?php

namespace App\Http\Controllers;

use App\Models\DeliveryZone;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeliveryZoneController extends Controller
{
    /** 🏠 List all delivery zones */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $zones = DeliveryZone::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('region', 'like', "%{$search}%")
                      ->orWhere('area', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('delivery/Index', [
            'zones' => $zones,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    /** ➕ Show create form */
    public function create()
    {
        return Inertia::render('delivery/Create');
    }

    /** 💾 Store new zone */
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

    /** ✏️ Edit page */
    public function edit(DeliveryZone $deliveryZone)
    {
        return Inertia::render('delivery/Edit', [
            'zone' => $deliveryZone,
        ]);
    }

    /** 🔄 Update existing zone */
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

    /** ❌ Delete zone */
    public function destroy(DeliveryZone $deliveryZone)
    {
        $deliveryZone->delete();

        return back()->with('success', 'Delivery Zone deleted successfully.');
    }

    /** ✅ Toggle Active/Inactive status */
    public function toggleStatus(DeliveryZone $deliveryZone)
    {
        $deliveryZone->update(['status' => !$deliveryZone->status]);

        return back()->with('success', 'Zone status updated successfully.');
    }
}
