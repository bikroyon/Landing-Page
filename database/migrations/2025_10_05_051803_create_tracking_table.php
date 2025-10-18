<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trackings', function (Blueprint $table) {
            $table->id();

            // Store guest/session info
            $table->string('session_id')->nullable(); // tie to Laravel session
            $table->ipAddress('ip')->nullable();      // guest IP
            $table->string('user_agent')->nullable(); // browser info

            // Store pixel/analytics info
            $table->string('fb_pixel_id')->nullable();
            $table->string('google_analytics_id')->nullable();

            // Optional: store event or page
            $table->string('event_name')->nullable();
            $table->json('event_data')->nullable(); // any extra data

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trackings');
    }
};
