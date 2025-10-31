<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\DeliveryZone;
use App\Models\PaymentMethod;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class LandingPageController extends Controller
{

    /**
     * Show landing page with products
     */
    public function index()
    {
        $settings = StoreSetting::with(['codMethod', 'bkashMethod', 'nagadMethod', 'rocketMethod'])->first();
        $products = Product::where('status', true)->get();
        $deliveryZones = DeliveryZone::all();
        $paymentMethods = PaymentMethod::all();

        return Inertia::render('LandingPage', [
            'user' => Auth::user(),
            'settings' => $settings,
            'products' => $products,
            'deliveryZones' => $deliveryZones,
            'paymentMethods' => $paymentMethods,
        ]);
    }



    /**
     * Store guest order
     */
    public function store(Request $request)
    {
        // 1️⃣ Validate Request
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:50',
            'customer_email' => 'nullable|email',
            'customer_address' => 'required|string',

            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.variant_index' => 'required|integer|min:0',
            'items.*.extra_price' => 'nullable|numeric|min:0',

            'delivery_zone_id' => 'required|exists:delivery_zones,id',
            'payment_method_id' => 'required|exists:payment_methods,id',

            'additional_note' => 'nullable|string',
            'transaction_id' => 'nullable|string|max:255',
            'transaction_mobile_number' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();

        // 2️⃣ Calculate Subtotal
        $subtotal = 0;
        foreach ($data['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $variantPrice = $product->variants[$item['variant_index']]['price'] ?? $product->price;
            $extra = $item['extra_price'] ?? 0;

            $subtotal += ($variantPrice + $extra) * $item['quantity'];
        }

        // 3️⃣ Delivery Fee
        $zone = DeliveryZone::findOrFail($data['delivery_zone_id']);
        $deliveryFee = $zone->effectiveFee($subtotal);

        // 4️⃣ Total Discount (currently 0)
        $totalDiscount = 0;

        // 5️⃣ Total Amount
        $totalAmount = $subtotal + $deliveryFee - $totalDiscount;

        // 6️⃣ Create Order
        $order = Order::create([
            'user_id' => $user?->id,
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'customer_email' => $data['customer_email'],
            'customer_address' => $data['customer_address'],

            'subtotal' => $subtotal,
            'delivery_fee' => $deliveryFee,
            'total_discount' => $totalDiscount,
            'total_amount' => $totalAmount,

            'delivery_zone_id' => $zone->id,
            'payment_method_id' => $data['payment_method_id'],

            'additional_note' => $data['additional_note'],
            'transaction_id' => $data['transaction_id'],
            'transaction_mobile_number' => $data['transaction_mobile_number'],

            'status' => 'pending',
        ]);

        // 7️⃣ Save Order Items
        foreach ($data['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $variantPrice = $product->variants[$item['variant_index']]['price'] ?? $product->price;
            $extra = $item['extra_price'] ?? 0;
            $price = $variantPrice + $extra;

            $order->items()->create([
                'product_id' => $product->id,
                'variant_index' => $item['variant_index'],
                'extra_price' => $extra,
                'quantity' => $item['quantity'],
                'price' => $price,
                'subtotal' => $price * $item['quantity'],
            ]);
        }

        return redirect()->route('order.thankyou', $order->id);
    }




    /**
     * Show thank you page after order
     */
    public function thankYou($orderId)
    {
        $order = Order::with('items.product')->findOrFail($orderId);

        $userExists = \App\Http\Controllers\Auth\RegisteredUserController::customerExists(
            $order->customer_email ?? null,
            $order->customer_phone
        );

        return Inertia::render('ThankYou', [
            'order' => $order,
            'userExists' => $userExists,
        ]);
    }
}
