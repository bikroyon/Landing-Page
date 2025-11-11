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
            $table->string('order_number', 6)->unique();
            $table->foreignId('delivery_zone_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->nullable()->constrained()->cascadeOnDelete();

            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email');
            $table->text('customer_address');
            $table->text('additional_note')->nullable();

            $table->string('transaction_id')->nullable();
            $table->string('transaction_mobile_number')->nullable();
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('total_discount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);

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
