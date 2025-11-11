<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('otherspages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // privacy-policy, terms, return-refund
            $table->string('title');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->longText('content'); // Page HTML/Text
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otherspages');
    }
};
