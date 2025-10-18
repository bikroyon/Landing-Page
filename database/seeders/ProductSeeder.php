<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'Cotton T-Shirt',
            'description' => 'Comfortable cotton T-shirt in multiple sizes',
            'price' => 500,
            'status' => true,
            'product_type' => 'physical',
            'variations' => [
                ['name' => 'Small', 'extra_price' => 0, 'sku' => 'TS-S', 'stock' => 50],
                ['name' => 'Medium', 'extra_price' => 50, 'sku' => 'TS-M', 'stock' => 40],
                ['name' => 'Large', 'extra_price' => 100, 'sku' => 'TS-L', 'stock' => 30],
            ],
            'image' => 'products/tshirt.png',
        ]);

        Product::create([
            'name' => 'E-book: Learn Laravel',
            'description' => 'Downloadable PDF guide for Laravel beginners',
            'price' => 300,
            'status' => true,
            'product_type' => 'digital',
            'downloads' => [
                ['name' => 'Laravel Guide PDF', 'file' => 'downloads/laravel.pdf', 'extra_price' => 0, 'max_downloads' => 5]
            ],
            'image' => 'products/ebook.png',
        ]);

        Product::create([
            'name' => 'Web Development Course',
            'description' => 'Weekly online course for learning web development',
            'price' => 2000,
            'status' => true,
            'product_type' => 'service',
            'service' => [
                'type' => 'course',
                'start_date' => '2025-10-01',
                'end_date' => '2025-12-31',
                'days_of_week' => ['Monday','Wednesday','Friday'],
                'time' => '18:00',
                'duration_per_session' => 2,
                'max_participants' => 20,
                'notes' => 'Bring your own laptop'
            ],
            'image' => 'products/course.png',
        ]);
    }
}
