<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Dhaka City", "Chattogram Zone 1"

            // Geographic coverage
            $table->string('region')->nullable(); // district or division
            $table->string('area')->nullable();   // sub-area, thana, zip code

            // Pricing rules
            $table->decimal('delivery_fee', 10, 2)->default(0); // base delivery charge
            $table->decimal('free_delivery_min_order', 10, 2)->nullable(); // free if order >= X

            // Status
            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_zones');
    }
};
