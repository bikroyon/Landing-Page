<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Order;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $completedOrders = Order::where('status', 'completed')->get();

        foreach ($completedOrders as $order) {
            foreach ($order->items as $item) {
                Review::create([
                    'user_id' => $order->user_id,
                    'product_id' => $item->product_id,
                    'order_id' => $order->id,
                    'rating' => rand(4, 5),
                    'comment' => 'This is a sample review for ' . $item->product->name,
                    'is_published' => true,
                ]);
            }
        }

        $this->command->info('Reviews seeded successfully.');
    }
}
