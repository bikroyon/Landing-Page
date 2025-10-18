<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        $query = Offer::query();

        if ($request->search) {
            $query->where('code', 'like', "%{$request->search}%");
        }

        $offers = $query->latest()->paginate(10)->withQueryString();

        return Inertia::render('offers/Index', [
            'offers' => $offers,
            'filters' => $request->only('search'),
        ]);
    }

    public function create()
    {
        return Inertia::render('offers/Form', [
            'offer' => null,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'offer_type' => 'required|in:coupon,delivery,product,cart',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric',
            'code' => 'nullable|string|unique:offers,code',
        ]);

        Offer::create($request->all());

        return redirect()->route('offers.index')->with('success', 'Offer created successfully.');
    }

    public function edit(Offer $offer)
    {
        return Inertia::render('offers/Form', [
            'offer' => $offer,
        ]);
    }

    public function update(Request $request, Offer $offer)
    {
        $request->validate([
            'offer_type' => 'required|in:coupon,delivery,product,cart',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric',
            'code' => 'nullable|string|unique:offers,code,' . $offer->id,
        ]);

        $offer->update($request->all());

        return redirect()->route('offers.index')->with('success', 'Offer updated successfully.');
    }

    public function destroy(Offer $offer)
    {
        $offer->delete();
        return redirect()->route('offers.index')->with('success', 'Offer deleted successfully.');
    }
}
