<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Offer;
use App\Models\DeliveryZone;
use App\Models\Product;
use Carbon\Carbon;

class OfferSeeder extends Seeder
{
    public function run(): void
    {
        $product = Product::first();
        $zone = DeliveryZone::first();

        // Coupon offer
        Offer::create([
            'offer_type' => 'coupon',
            'code' => 'WELCOME10',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'min_order_amount' => 200,
            'max_discount' => 500,
            'active' => true,
            'starts_at' => Carbon::now()->subDay(),
            'expires_at' => Carbon::now()->addMonth(),
        ]);

        // Delivery zone offer
        Offer::create([
            'offer_type' => 'delivery',
            'discount_type' => 'fixed',
            'discount_value' => 50,
            'delivery_zone_id' => $zone->id,
            'min_order_amount' => 500,
            'active' => true,
            'starts_at' => Carbon::now()->subDay(),
            'expires_at' => Carbon::now()->addMonth(),
        ]);

        // Product-specific offer
        Offer::create([
            'offer_type' => 'product',
            'product_id' => $product->id,
            'discount_type' => 'percentage',
            'discount_value' => 20,
            'active' => true,
            'starts_at' => Carbon::now()->subDay(),
            'expires_at' => Carbon::now()->addMonth(),
        ]);

        // Cart-wide offer
        Offer::create([
            'offer_type' => 'cart',
            'discount_type' => 'fixed',
            'discount_value' => 100,
            'min_order_amount' => 2000,
            'active' => true,
            'starts_at' => Carbon::now()->subDay(),
            'expires_at' => Carbon::now()->addMonth(),
        ]);
    }
}
