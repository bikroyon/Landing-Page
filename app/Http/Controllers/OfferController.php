<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::latest()->paginate(10);

        return Inertia::render('offers/Index', [
            'offers' => $offers,
        ]);
    }

    public function create()
    {
        $products = Product::select('id', 'name', 'price')->get();

        return Inertia::render('offers/Create', [
            'products' => $products,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'offer_type' => 'required|in:coupon,product,cart',
            'code' => 'nullable|string|unique:offers,code',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'product_id' => 'nullable|array',
            'product_id.*' => 'exists:products,id',
            'usage_limit' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'active' => 'boolean',
        ]);

        $validated['product_id'] = $request->product_id ? json_encode($request->product_id) : null;

        Offer::create($validated);

        return redirect()->route('offers.index')->with('success', 'Offer created successfully.');
    }

    public function edit(Offer $offer)
    {
        $products = Product::select('id', 'name', 'price')->get();

        return Inertia::render('offers/Edit', [
            'offer' => $offer,
            'products' => $products,
        ]);
    }

    public function update(Request $request, Offer $offer)
    {
        $validated = $request->validate([
            'offer_type' => 'required|in:coupon,product,cart',
            'code' => 'nullable|string|unique:offers,code,' . $offer->id,
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'product_id' => 'nullable|array',
            'product_id.*' => 'exists:products,id',
            'usage_limit' => 'nullable|integer|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'active' => 'boolean',
        ]);

        $validated['product_id'] = $request->product_id ? json_encode($request->product_id) : null;

        $offer->update($validated);

        return redirect()->route('offers.index')->with('success', 'Offer updated successfully.');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();

        return redirect()->route('offers.Index')->with('success', 'Offer deleted.');
    }
}
