<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trackings', function (Blueprint $table) {
            $table->id();

            // Facebook Pixel
            $table->string('facebook_pixel_id')->nullable();
            $table->string('facebook_access_token')->nullable();
            $table->boolean('facebook_test_event')->default(false);
            $table->string('facebook_test_event_code')->nullable();

            // Google
            $table->string('google_tag_manager_id')->nullable();
            $table->string('google_analytics_id')->nullable();

            // Optional Data Layer / extra configs
            $table->json('data_layer')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trackings');
    }
};
