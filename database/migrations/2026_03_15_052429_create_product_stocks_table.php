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
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('opening_quantity', 15, 2)->default(0);
            $table->decimal('current_quantity', 15, 2)->default(0);
            $table->decimal('opening_cost', 15, 2)->nullable();
            $table->decimal('alert_quantity', 15, 2)->nullable();
            $table->timestamps();

            $table->unique(['warehouse_id', 'product_id']);
            $table->index(['store_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stocks');
    }
};
