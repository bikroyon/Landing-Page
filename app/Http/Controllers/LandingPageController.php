<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\DeliveryZone;
use App\Models\PaymentMethod;
use App\Models\Offer;
use App\Services\OfferService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class LandingPageController extends Controller
{
    protected $offerService;

    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    /**
     * Show landing page with products
     */
    public function index()
    {
        $products = Product::where('status', true)->get();

        $deliveryZones = DeliveryZone::all();
        $paymentMethods = PaymentMethod::all();

        return Inertia::render('LandingPage', [
            'user' => Auth::user(),
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
    $data = $request->validate([
        'customer_name' => 'required|string|max:255',
        'customer_phone' => 'required|string|max:20',
        'customer_email' => 'nullable|email|max:255',
        'customer_address' => 'required|string|max:500',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'delivery_zone_id' => 'nullable|exists:delivery_zones,id',
        'payment_method_id' => 'nullable|exists:payment_methods,id',
        'coupon_code' => 'nullable|string|exists:offers,code',
    ]);

    $user = Auth::user();

    // ✅ Use logged-in user's info if available
    $customerName = $user ? $user->name : $data['customer_name'];
    $customerPhone = $user ? $user->phone : $data['customer_phone'];
    $customerEmail = $user ? $user->email : $data['customer_email'] ?? null;
    $customerAddress = $user ? $user->address : $data['customer_address'];

    // Calculate subtotal
    $subtotal = 0;
    foreach ($data['items'] as $item) {
        $product = Product::find($item['product_id']);
        $subtotal += $product->price * $item['quantity'];
    }

    // Offers + discount
    $appliedOffers = $this->offerService->getApplicableOffers($data);
    $totalDiscount = $this->offerService->calculateTotalDiscount($data);

    // Delivery fee
    $deliveryFee = 0;
    if (!empty($data['delivery_zone_id'])) {
        $deliveryZone = DeliveryZone::find($data['delivery_zone_id']);
        $deliveryFee = $deliveryZone?->effectiveFee($subtotal) ?? 0;
    }

    $totalAmount = max(0, $subtotal + $deliveryFee - $totalDiscount);

    // ✅ Create order linked to user if logged in
    $order = Order::create([
        'user_id' => $user?->id,
        'customer_name' => $customerName,
        'customer_phone' => $customerPhone,
        'customer_email' => $customerEmail,
        'customer_address' => $customerAddress,
        'subtotal' => $subtotal,
        'total_amount' => $totalAmount,
        'delivery_zone_id' => $data['delivery_zone_id'] ?? null,
        'payment_method_id' => $data['payment_method_id'] ?? null,
        'offer_id' => $appliedOffers[0]->id ?? null,
        'status' => 'pending',
    ]);

    // Create order items
    foreach ($data['items'] as $item) {
        $product = Product::find($item['product_id']);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $item['quantity'],
            'price' => $product->price,
            'subtotal' => $product->price * $item['quantity'],
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
