<?php

namespace App\Http\Controllers;

use App\Models\Order;
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


    public function show(Order $order)
    {
        return Inertia::render('orders/Show', [
            'order' => $order->load('items.product')
        ]);
    }

    public function updateStatus(Request $request, $order_number)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $order = Order::where('order_number', $order_number)->firstOrFail();
        $oldStatus = $order->status;
        $order->update([
            'status' => $request->status
        ]);

        return back();
    }



    public function destroy(Order $order)
    {
        $order->delete();
        return back()->with('success', 'Order deleted!');
    }



    public function myOrders()
    {
        $user = Auth::user();

        $orders = Order::with(['items.product', 'paymentMethod', 'deliveryZone'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        // Map each order like the thankyou page
        $mappedOrders = $orders->through(function ($order) {
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

            return [
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
                'status' => $order->status,
                'items' => $items,
            ];
        });

        return Inertia::render('orders/MyOrders', [
            'orders' => $mappedOrders->toArray() + [
                'links' => $orders->links(),
                'current_page' => $orders->currentPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
        ]);
    }
    public function cancel(Order $order, Request $request)
    {
        //validate note input
        $request->validate([
            'note' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        if ($order->user_id !== $user->id) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return back()->withErrors('Order cannot be cancelled at this stage.');
        }

        $order->status = 'cancelled';
        $order->note = $request->input('note', null);
        $order->save();

        return redirect()->back()->with('success', 'Order cancelled successfully.');
    }
}
