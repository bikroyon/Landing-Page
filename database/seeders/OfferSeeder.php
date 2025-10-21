<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Offer;
use App\Models\Product;
use Carbon\Carbon;

class OfferSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::pluck('id')->toArray();

        Offer::create([
            'offer_type' => 'coupon',
            'code' => 'WELCOME10',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'min_order_amount' => 500,
            'max_discount' => 200,
            'product_id' => null,
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonth(),
            'active' => true,
        ]);

        Offer::create([
            'offer_type' => 'product',
            'code' => null,
            'discount_type' => 'fixed',
            'discount_value' => 50,
            'min_order_amount' => 0,
            'max_discount' => null,
            'product_id' => json_encode(array_slice($products, 0, 3)),
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addWeeks(2),
            'active' => true,
        ]);

        Offer::create([
            'offer_type' => 'cart',
            'code' => null,
            'discount_type' => 'percentage',
            'discount_value' => 15,
            'min_order_amount' => 1000,
            'max_discount' => 500,
            'product_id' => null,
            'starts_at' => Carbon::now(),
            'expires_at' => Carbon::now()->addMonth(),
            'active' => true,
        ]);
    }
}
