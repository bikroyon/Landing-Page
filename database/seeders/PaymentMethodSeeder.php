<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        // Cash on Delivery
        PaymentMethod::create([
            'name' => 'Cash on Delivery',
            'code' => 'COD',
            'type' => 'cod',
            'status' => true,
        ]);

        // Mobile Banking: Bkash
        PaymentMethod::create([
            'name' => 'Bkash',
            'code' => 'BKASH',
            'type' => 'mobile',
            'provider' => 'Bkash',
            'account_number' => '017XXXXXXXX',
            'qr_code' =>  'https://placehold.co/600x400?text=QR',
            'status' => true,
        ]);

        // Mobile Banking: Nagad
        PaymentMethod::create([
            'name' => 'Nagad',
            'code' => 'NAGAD',
            'type' => 'mobile',
            'provider' => 'Nagad',
            'account_number' => '018XXXXXXXX',
            'qr_code' => 'https://placehold.co/600x400?text=QR',
            'status' => true,
        ]);

        // Mobile Banking: Nagad
        PaymentMethod::create([
            'name' => 'Rocket',
            'code' => 'ROCKET',
            'type' => 'mobile',
            'provider' => 'Rocket',
            'account_number' => '018XXXXXXXX',
            'qr_code' =>  'https://placehold.co/600x400?text=QR',
            'status' => true,
        ]);
    }
}
