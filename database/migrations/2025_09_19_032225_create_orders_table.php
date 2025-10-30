<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('delivery_zone_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained()->cascadeOnDelete();

            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email');
            $table->text('customer_address');

            $table->string('transaction_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('transaction_number')->nullable();

            $table->decimal('subtotal', 10, 2)->default(0); // sum of item subtotals
            $table->decimal('total_amount', 10, 2)->default(0); // subtotal + delivery_fee - discount

            $table->enum('status', [
                'pending',
                'confirmed',
                'processing',
                'packaging',
                'shared_to_courier',
                'delivered',
                'completed',
                'cancelled',
                'returned',
                'refunded'
            ])->default('pending');

            $table->text('note')->nullable()->comment('Remarks for cancellation or order updates');

            $table->timestamps();
            $table->softDeletes(); // optional, keeps history of deleted orders
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
