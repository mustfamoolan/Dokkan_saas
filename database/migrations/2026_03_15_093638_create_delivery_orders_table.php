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
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('sales_invoice_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('representative_id')->nullable()->constrained()->onDelete('set null');
            $table->string('order_number')->unique();
            $table->date('order_date');
            $table->string('status')->default('new'); // new, preparing, out_for_delivery, delivered, cancelled
            $table->text('delivery_address')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            
            // Unique within store for order_number (if we want per-store unique, but let's stick to global unique for now or adjust)
            // $table->unique(['store_id', 'order_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_orders');
    }
};
