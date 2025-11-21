<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\DeliveryZone;
use App\Models\PaymentMethod;
use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Helpers\FacebookCAPI;

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
            'customer_phone' => 'required|string|max:50|min:11|max:11',
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
        ], [

            // ✅ Customer Info Messages
            'customer_name.required' => 'নাম অবশ্যই দিতে হবে।',
            'customer_phone.required' => 'মোবাইল নম্বর অবশ্যই দিতে হবে।',
            'customer_phone.min' => 'মোবাইল নম্বর অবশ্যই ১১ সংখ্যার হতে হবে।',
            'customer_phone.max' => 'মোবাইল নম্বর অবশ্যই ১১ সংখ্যার হতে হবে।',

            'customer_email.email' => 'সঠিক ই-মেইল দিন (যেমনঃ example@gmail.com)',
            'customer_address.required' => 'ঠিকানা অবশ্যই দিতে হবে।',

            // ✅ Items Messages
            'items.required' => 'কমপক্ষে একটি পণ্য সিলেক্ট করুন।',
            'items.min' => 'কমপক্ষে একটি পণ্য যোগ করতে হবে।',
            'items.*.product_id.required' => 'পণ্য নির্বাচন করা হয়নি।',
            'items.*.product_id.exists' => 'সিলেক্ট করা পণ্য পাওয়া যায়নি।',
            'items.*.quantity.required' => 'পণ্যের পরিমাণ দিতে হবে।',
            'items.*.quantity.min' => 'কমপক্ষে ১টি পণ্য অর্ডার করতে হবে।',

            // ✅ Delivery / Payment
            'delivery_zone_id.required' => 'ডেলিভারি এরিয়া নির্বাচন করুন।',
            'delivery_zone_id.exists' => 'ডেলিভারি এরিয়া সঠিক নয়।',
            'payment_method_id.required' => 'পেমেন্ট মেথড নির্বাচন করুন।',
            'payment_method_id.exists' => 'পেমেন্ট মেথড সঠিক নয়।',

            // ✅ Payment Information
            'transaction_id.max' => 'লেনদেন নম্বর সঠিক নয়।',
            'transaction_mobile_number.max' => 'লেনদেন করা মোবাইল নম্বর সঠিক নয়।',
        ]);
        // ✅ Generate unique order number
        $data['order_number'] = Order::generateOrderNumber();

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
            'order_number' => $data['order_number'],
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
        // 7 Save Order Items
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
        // Generate unique event ID for deduplication
        $eventId = uniqid();

        // Prepare contents array
        $contents = $order->items->map(function ($item) {
            return [
                'id' => $item->product_id,
                'quantity' => $item->quantity,
                'item_price' => $item->price,
            ];
        })->toArray();

        // Prepare user data (hashed)
        $userData = [
            'em' => $order->customer_email ? hash('sha256', $order->customer_email) : null,
            'ph' => $order->customer_phone ? hash('sha256', $order->customer_phone) : null,
        ];

        // Send Purchase event
        FacebookCAPI::sendEvent([
            'event_name' => 'Purchase',
            'event_time' => time(),
            'event_id' => $eventId, // deduplication
            'user_data' => array_filter($userData),
            'custom_data' => [
                'currency' => 'BDT',
                'value' => $order->total_amount,
                'content_ids' => $order->items->pluck('product_id')->toArray(),
                'contents' => $contents,
                'num_items' => $order->items->count(),
            ],
            'action_source' => 'website',
        ]);


        return redirect()->route('order.thankyou', $order->order_number);
    }




    /**
     * Show thank you page after order
     */
    public function thankYou($order_number)
    {
        $order = Order::with(['items.product', 'paymentMethod', 'deliveryZone'])
            ->where('order_number', $order_number)
            ->firstOrFail();
        $settings = StoreSetting::first();
        $userExists = RegisteredUserController::customerExists(
            $order->customer_email ?? null,
            $order->customer_phone
        );

        // Map items to include variant info
        $items = $order->items->map(function ($item) {
            return [
                'id' => $item->id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->subtotal,
                'variant_index' => $item->variant_index,
                'extra_price' => $item->extra_price,
                'product' => [
                    'id' => $item->product->id,
                    'name' => $item->product->name,
                    'price' => $item->product->price,
                    'image' => $item->product->image,
                    'variations' => $item->product->variations,
                ],
            ];
        });

        return Inertia::render('ThankYou', [
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'customer_name' => $order->customer_name,
                'customer_email' => $order->customer_email,
                'customer_phone' => $order->customer_phone,
                'customer_address' => $order->customer_address,
                'payment_method' => $order->paymentMethod?->name ?? 'N/A',
                'delivery_zone' => $order->deliveryZone?->name ?? 'N/A',
                'delivery_fee' => $order->delivery_fee,
                'subtotal' => $order->subtotal,
                'total_discount' => $order->total_discount,
                'total_amount' => $order->total_amount,
                'items' => $items,
                'note' => $order->note,
            ],
            'userExists' => $userExists,
            'settings' => $settings,
        ]);
    }
}
