<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\DeliveryZone;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrderController extends Controller
{

public function index(Request $request)
{
    $query = Order::with(['items.product']); // ensure items & product are loaded

    if ($request->search) {
        $query->where('customer_name', 'like', "%{$request->search}%")
              ->orWhere('customer_email', 'like', "%{$request->search}%")
              ->orWhere('customer_phone', 'like', "%{$request->search}%");
    }

    $orders = $query->latest()->paginate(2)->withQueryString();

    return Inertia::render('orders/Index', [
        'orders' => $orders,
        'filters' => $request->only('search'),
    ]);
}



public function store(Request $request)
{
    $request->validate([
        'customer_name' => 'required|string',
        'customer_phone' => 'required|string',
        'customer_email' => 'nullable|email',
        'customer_address' => 'required|string',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'delivery_zone_id' => 'nullable|exists:delivery_zones,id',
        'payment_method_id' => 'nullable|exists:payment_methods,id',
        'coupon_code' => 'nullable|string',
    ]);
    $user = Auth::user(); // ✅ This must come BEFORE any form-based data

    // If user is logged in, ignore frontend fields and take values from user
    $customerName = $user ? $user->name : $request->customer_name;
    $customerPhone = $user ? $user->phone : $request->customer_phone;
    $customerEmail = $user ? $user->email : $request->customer_email;
    $customerAddress = $user ? $user->address : $request->customer_address;
    // Calculate totals
    $subtotal = 0;
    foreach ($request->items as $item) {
        $product = Product::find($item['product_id']);
        $subtotal += $product->price * $item['quantity'];
    }

    $deliveryFee = $request->delivery_zone_id
        ? DeliveryZone::find($request->delivery_zone_id)->fee
        : 0;




    // ✅ Create order and link to user (if available)
    $order = Order::create([
        'user_id' => $user?->id, // attach logged-in user automatically
        'customer_name' => $customerName,
        'customer_phone' => $customerPhone,
        'customer_email' => $customerEmail,
        'customer_address' => $customerAddress,
        'subtotal' => $subtotal,
        'total_amount' => $subtotal + $deliveryFee - $discount,
        'delivery_zone_id' => $request->delivery_zone_id,
        'payment_method_id' => $request->payment_method_id,
    ]);

    // Create items
    foreach ($request->items as $item) {
        $product = Product::find($item['product_id']);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $item['quantity'],
            'price' => $product->price,
            'subtotal' => $product->price * $item['quantity'],
        ]);
    }

    // ✅ Redirect to Thank You Page with order data
    return redirect()->route('thankyou', ['order' => $order->id]);
}

    public function show(Order $order)
    {
        return Inertia::render('orders/Show', ['order' => $order->load('items.product')]);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->back()->with('success', 'Order deleted!');
    }

        public function myOrders(Request $request)
    {
        $user = Auth::user();

        $orders = Order::with(['items.product'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return Inertia::render('orders/MyOrders', [
            'orders' => $orders,
        ]);
    }
}
