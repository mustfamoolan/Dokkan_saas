<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gift_points_exceptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('representative_id')->nullable()->constrained('representatives')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->integer('points_per_order')->default(0); // Specific points for this rep/user
            $table->boolean('is_active')->default(true); // Toggle for this specific exception
            $table->timestamps();

            // Ensure only one of representative_id or user_id is set
            // In Laravel, we typically handle this logic in the application layer or via DB constraints if supported
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_points_exceptions');
    }
};
