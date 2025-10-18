<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Cash on Delivery", "Bkash"
            $table->string('code')->unique(); // e.g. "COD", "BKASH", "NAGAD"
            $table->enum('type', ['cod', 'mobile']); // Only COD & Mobile banking

            $table->string('provider')->nullable();   // For mobile, e.g. "Bkash"
            $table->string('account_number')->nullable(); // Merchant/Agent number
            $table->string('qr_code')->nullable();    // Path or URL of QR code image
            $table->string('logo')->nullable();       // Path or URL of logo image

            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
