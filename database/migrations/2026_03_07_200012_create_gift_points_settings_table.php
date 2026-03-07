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
        Schema::create('gift_points_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('points_per_order')->default(0); // Default points added per normal order completion
            $table->boolean('is_active')->default(true); // Global toggle for gift points system
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_points_settings');
    }
};
