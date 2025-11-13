<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('store_settings', function (Blueprint $table) {
            $table->id();

            // Basic Info
            $table->string('store_name')->default('My Store');
            $table->string('store_tagline')->nullable();
            $table->string('store_logo')->nullable();
            $table->string('store_favicon')->nullable();
            // Meta / SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            // Contact Info
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();
            // Social Links
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('tiktok_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('youtube_url')->nullable();
            // Business Settings
            $table->string('currency')->default('BDT');
            $table->string('currency_symbol')->default('৳');
            $table->boolean('maintenance_mode')->default(false);


            // Payment Method IDs
            $table->foreignId('cod')->nullable()->constrained('payment_methods');
            $table->foreignId('bkash')->nullable()->constrained('payment_methods');
            $table->foreignId('nagad')->nullable()->constrained('payment_methods');
            $table->foreignId('rocket')->nullable()->constrained('payment_methods');

            // Section Titles
            $table->string('products_title')->default('Products');
            $table->string('customer_info_title')->default('Customer Information');
            $table->string('customer_info_label')->default(1);
            $table->string('customer_info_name_label')->default('Enter Name');
            $table->string('customer_info_phone_label')->default('Enter Phone');
            $table->string('customer_info_email_label')->default('Enter Email');
            $table->string('customer_info_address_label')->default('Enter Address');
            $table->string('delivery_zone_title')->default('Delivery Zone');
            $table->string('additional_note_title')->default('Additional Notes');
            $table->string('additional_note_title_status')->default('Additional Notes');
            $table->string('order_summary_title')->default('Order Summary');
            $table->string('payment_title')->default('Payment Method');
            $table->string('submit_button')->default('Place Order');
            $table->string('fb_pixel_id')->nullable();
            $table->string('fb_pixel_access_token')->nullable();
             $table->string('fraudchecker_api_key')->nullable();
            // Email / SMTP
            $table->string('mail_driver')->default('smtp');
            $table->string('mail_host')->nullable();
            $table->integer('mail_port')->default(587);
            $table->string('mail_username')->nullable();
            $table->string('mail_password')->nullable();
            $table->string('mail_encryption')->default('tls');
            $table->string('mail_from_address')->nullable();
            $table->string('mail_from_name')->nullable();

            // SMS Gateway
            $table->string('sms_api_url')->nullable();
            $table->string('sms_api_key')->nullable();
            $table->string('sms_sender_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('store_settings');
    }
};
