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
        Schema::table('users', function (Blueprint $table) {
            // Add foreign key to roles table (assuming id=1=admin, 2=customer, etc.)
            $table->foreignId('role_id')
                ->default(2) // default customer
                ->constrained('roles')
                ->cascadeOnDelete()
                ->after('id');
                 $table->boolean('is_banned')->default(false)->after('role_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id');
            $table->dropColumn('is_banned');
        });
    }
};
