<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\DeliveryZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['items.product']);

        if ($request->search) {
            $query->where('customer_name', 'like', "%{$request->search}%")
                ->orWhere('customer_email', 'like', "%{$request->search}%")
                ->orWhere('customer_phone', 'like', "%{$request->search}%");
        }

        return Inertia::render('orders/Index', [
            'orders' => $query->latest()->paginate(10)->withQueryString(),
            'filters' => $request->only('search'),
        ]);
    }



    public function store(Request $request)
    {
        // ✅ Validate Request
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
            'trabsaction_mobile_number' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();


        // ✅ Calculate Subtotal (With Variant + Extras)
        $subtotal = 0;

        foreach ($data['items'] as $item) {
            $product = Product::find($item['product_id']);

            $variantIndex = $item['variant_index'];
            $variantPrice = $product->variants[$variantIndex]['price'] ?? $product->price;

            $extra = $item['extra_price'] ?? 0;

            $subtotal += ($variantPrice + $extra) * $item['quantity'];
        }


        // ✅ Delivery Fee Logic From DeliveryZone Model
        $zone = DeliveryZone::find($data['delivery_zone_id']);

        $deliveryFee = $zone->effectiveFee($subtotal);  // ✅ FREE IF SUBTOTAL ≥ free_delivery_min_order

        // ✅ Total (No Discount)
        $totalAmount = $subtotal + $deliveryFee;


        // ✅ Create Order
        $order = Order::create([
            'user_id'        => $user?->id,
            'customer_name'  => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'customer_email' => $data['customer_email'],
            'customer_address' => $data['customer_address'],

            'subtotal'       => $subtotal,
            'delivery_fee'   => $deliveryFee,
            'total_amount'   => $totalAmount,

            'delivery_zone_id' => $zone->id,
            'payment_method_id' => $data['payment_method_id'],

            'additional_note' => $data['additional_note'],
            'transaction_id' => $data['transaction_id'],
            'trabsaction_mobile_number' => $data['trabsaction_mobile_number'],

            'status' => 'pending',
        ]);


        // ✅ Save Order Items
        foreach ($data['items'] as $item) {
            $product = Product::find($item['product_id']);

            $variantIndex = $item['variant_index'];
            $variantPrice = $product->variants[$variantIndex]['price'] ?? $product->price;

            $extra = $item['extra_price'] ?? 0;
            $finalPrice = $variantPrice + $extra;

            $order->items()->create([
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_price' => $finalPrice,
                'quantity' => $item['quantity'],
                'total_price' => $finalPrice * $item['quantity'],
            ]);
        }


        return redirect()->route('order.thankyou', $order->id);
    }





    public function show(Order $order)
    {
        return Inertia::render('orders/Show', [
            'order' => $order->load('items.product')
        ]);
    }



    public function destroy(Order $order)
    {
        $order->delete();
        return back()->with('success', 'Order deleted!');
    }



    public function myOrders()
    {
        $user = Auth::user();

        return Inertia::render('orders/MyOrders', [
            'orders' => Order::with(['items.product'])
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(10)
        ]);
    }
}
