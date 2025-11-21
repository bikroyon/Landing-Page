<?php

namespace App\Http\Controllers;

use App\Helpers\AccessHelper;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        if (!AccessHelper::hasAccess('products', 'view')) {
            abort(403, 'Unauthorized');
        }

        $search = $request->input('search');

        $products = Product::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'asc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('products/Index', [
            'products' => $products,
            'filters' => ['search' => $search],
        ]);
    }

    public function show(Product $product)
    {
        // If you don't need it, you can just return abort
        abort(404);
    }

    public function create()
    {
        if (!AccessHelper::hasAccess('products', 'create')) {
            abort(403, 'Unauthorized');
        }
        return Inertia::render('products/Create');
    }

    public function store(Request $request)
    {
        if (!AccessHelper::hasAccess('products', 'create')) {
            abort(403, 'Unauthorized');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'prev_price' => 'nullable|numeric',
            'cost_price' => 'nullable|numeric',
            'status' => 'boolean',
            'variations' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'variation_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $variations = [];
        if (!empty($validated['variations'])) {
            $variations = json_decode($validated['variations'], true);
        }

        // Handle main image
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = 'storage/' . $path;
        }

        // Handle variation images
        if ($request->hasFile('variation_images')) {
            foreach ($request->file('variation_images') as $index => $file) {
                $path = $file->store('products/variations', 'public');
                if (isset($variations[$index])) {
                    $variations[$index]['image'] = 'storage/' . $path;
                }
            }
        }

        $validated['variations'] = $variations;

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        if (!AccessHelper::hasAccess('products', 'edit')) {
            abort(403, 'Unauthorized');
        }
        return Inertia::render('products/Edit', [
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        if (!AccessHelper::hasAccess('products', 'edit')) {
            abort(403, 'Unauthorized');
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'prev_price' => 'nullable|numeric',
            'cost_price' => 'nullable|numeric',
            'status' => 'boolean',
            'variations' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'variation_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Decode variations JSON
        $variations = [];
        if (!empty($validated['variations'])) {
            $variations = json_decode($validated['variations'], true);
        }

        // ✅ Handle new main image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && file_exists(public_path($product->image))) {
                @unlink(public_path($product->image));
            }

            // Upload and save new image
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = 'storage/' . $path;
        } else {
            // Keep old image if not replaced
            $validated['image'] = $product->image;
        }

        // ✅ Handle variation images
        if ($request->hasFile('variation_images')) {
            foreach ($request->file('variation_images') as $index => $file) {
                $path = $file->store('products/variations', 'public');
                if (isset($variations[$index])) {
                    // Delete old variation image if exists
                    if (!empty($variations[$index]['image']) && file_exists(public_path($variations[$index]['image']))) {
                        @unlink(public_path($variations[$index]['image']));
                    }
                    $variations[$index]['image'] = 'storage/' . $path;
                }
            }
        }

        $validated['variations'] = $variations;

        // ✅ Finally update product
        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        if (!AccessHelper::hasAccess('products', 'delete')) {
            abort(403, 'Unauthorized');
        }
        // Delete main image
        if ($product->image && file_exists(public_path($product->image))) {
            @unlink(public_path($product->image));
        }

        // Delete variation images
        foreach ($product->variations ?? [] as $variation) {
            if (!empty($variation['image']) && file_exists(public_path($variation['image']))) {
                @unlink(public_path($variation['image']));
            }
        }

        $product->delete();

        return back()->with('success', 'Product deleted!');
    }
}
