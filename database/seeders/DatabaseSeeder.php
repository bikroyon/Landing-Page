<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProductSeeder::class,
            ActionSeeder::class,
            MenuSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            AccessToRoleSeeder::class,
                DeliveryZoneSeeder::class,
    PaymentMethodSeeder::class,
    OfferSeeder::class,
    OrderSeeder::class,
    ReviewSeeder::class,
        ]);
    }
}
