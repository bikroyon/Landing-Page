<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DeliveryZone;

class DeliveryZoneSeeder extends Seeder
{
    public function run(): void
    {
        DeliveryZone::create([
            'name' => 'Dhaka City',
            'region' => 'Dhaka',
            'area' => 'Gulshan',
            'delivery_fee' => 50,
            'free_delivery_min_order' => 500,
            'status' => true,
        ]);

        DeliveryZone::create([
            'name' => 'Chattogram Zone 1',
            'region' => 'Chattogram',
            'area' => 'Pahartali',
            'delivery_fee' => 60,
            'free_delivery_min_order' => 600,
            'status' => true,
        ]);
    }
}
