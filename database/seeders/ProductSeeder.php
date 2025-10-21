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
            'description' => 'Comfortable cotton T-shirt in multiple sizes.',
            'price' => 500,
            'status' => true,
            'variations' => [
                ['name' => 'Small', 'extra_price' => 0, 'sku' => 'TS-S', 'stock' => 50],
                ['name' => 'Medium', 'extra_price' => 50, 'sku' => 'TS-M', 'stock' => 40],
                ['name' => 'Large', 'extra_price' => 100, 'sku' => 'TS-L', 'stock' => 30],
            ],
            'image' => 'https://placehold.co/600x400',
        ]);

        Product::create([
            'name' => 'Leather Wallet',
            'description' => 'Genuine leather wallet with multiple compartments.',
            'price' => 1200,
            'status' => true,
            'variations' => [
                ['name' => 'Brown', 'extra_price' => 0, 'sku' => 'LW-BR', 'stock' => 25],
                ['name' => 'Black', 'extra_price' => 0, 'sku' => 'LW-BL', 'stock' => 20],
            ],
            'image' => 'https://placehold.co/600x400',
        ]);

        Product::create([
            'name' => 'Running Shoes',
            'description' => 'Lightweight running shoes for all terrains.',
            'price' => 3000,
            'status' => true,
            'variations' => [
                ['name' => 'Size 8', 'extra_price' => 0, 'sku' => 'RS-8', 'stock' => 15],
                ['name' => 'Size 9', 'extra_price' => 0, 'sku' => 'RS-9', 'stock' => 10],
                ['name' => 'Size 10', 'extra_price' => 0, 'sku' => 'RS-10', 'stock' => 5],
            ],
            'image' => 'https://placehold.co/600x400',
        ]);
    }
}
