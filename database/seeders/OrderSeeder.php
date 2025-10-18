<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\DeliveryZone;
use App\Models\PaymentMethod;
use App\Models\Offer;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::where('role_id', 2)->first();
        if (!$customer) {
            $this->command->info('No customer found. Skipping orders seeding.');
            return;
        }

        $products = Product::take(2)->get();
        if ($products->isEmpty()) {
            $this->command->info('No products found. Skipping orders seeding.');
            return;
        }

        $zone = DeliveryZone::first();
        $payment = PaymentMethod::first();
        $offer = Offer::where('offer_type', 'coupon')->first();

        foreach ($products as $product) {
            $order = Order::create([
                'user_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => $customer->phone,
                'customer_address' => $customer->address,
                'delivery_zone_id' => $zone?->id,
                'payment_method_id' => $payment?->id,
                'offer_id' => $offer?->id,
                'subtotal' => $product->price,
                'total_amount' => $product->price,
                'status' => 'completed',
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->price,
                'subtotal' => $product->price,
            ]);
        }

        $this->command->info('Orders seeded successfully.');
    }
}
