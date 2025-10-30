<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StoreSetting;
use App\Models\PaymentMethod;

class StoreSettingSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch payment method IDs
        $cod = PaymentMethod::where('code', 'COD')->first()?->id;
        $bkash = PaymentMethod::where('code', 'BKASH')->first()?->id;
        $nagad = PaymentMethod::where('code', 'NAGAD')->first()?->id;
        $rocket = PaymentMethod::where('code', 'ROCKET')->first()?->id;

        // Create or update store settings
        StoreSetting::updateOrCreate(
            ['id' => 1], // Assuming only one record exists
            [
                'store_name' => 'My Store',
                'store_tagline' => 'Best online shop',
                'store_logo' => 'https://placehold.co/600x400?text=Logo',
                'store_favicon' => 'https://placehold.co/60x60?text=Favicon',
                'email' => 'info@mystore.com',
                'phone' => '+8801700000000',
                'address' => 'Dhaka, Bangladesh',
                'city' => 'Dhaka',
                'country' => 'Bangladesh',
                'postal_code' => '1207',
                'currency' => 'BDT',
                'currency_symbol' => '৳',
                'maintenance_mode' => false,
                'facebook_url' => 'https://facebook.com/mystore',
                'instagram_url' => 'https://instagram.com/mystore',
                'tiktok_url' => 'https://tiktok.com/@mystore',
                'twitter_url' => 'https://twitter.com/mystore',
                'youtube_url' => 'https://youtube.com/mystore',
                'cod' => $cod,
                'bkash' => $bkash,
                'nagad' => $nagad,
                'rocket' => $rocket,
                'meta_title' => 'My Store Online',
                'meta_description' => 'Welcome to My Store, the best online shop.',
                'products_title' => 'Products',
                'customer_info_title' => 'Customer Information',
                'delivery_zone_title' => 'Delivery Zone',
                'additional_note_title' => 'Additional Notes',
                'order_summary_title' => 'Order Summary',
                'payment_title' => 'Payment Method',
                'submit_button' => 'Place Order',
            ]
        );
    }
}
