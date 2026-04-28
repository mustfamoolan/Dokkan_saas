<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add columns to categories
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'image')) {
                $table->string('image')->nullable();
            }
            if (!Schema::hasColumn('categories', 'parent_id')) {
                $table->integer('parent_id')->nullable();
            }
        });

        // Create Purchase Invoices
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('cascade');
            $table->string('invoice_number')->unique();
            $table->string('real_invoice_number')->nullable();
            $table->date('invoice_date');
            $table->decimal('driver_cost', 15, 2)->default(0);
            $table->decimal('workers_cost', 15, 2)->default(0);
            $table->boolean('costs_on_supplier')->default(false);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->decimal('discount', 15, 2)->default(0);
            $table->timestamps();
        });

        // Create Purchase Invoice Items
        Schema::create('purchase_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('purchase_invoices')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('cartons', 10, 2);
            $table->integer('units_per_carton');
            $table->decimal('purchase_price', 15, 2);
            $table->decimal('cost_per_carton', 15, 2);
            $table->decimal('retail_price', 15, 2);
            $table->decimal('wholesale_price', 15, 2);
            $table->boolean('is_gift')->default(false);
            $table->timestamps();
        });

        // Create Material Movements
        Schema::create('material_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('type'); // IN, OUT
            $table->string('reason'); // purchase, sale, etc.
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->decimal('cartons', 10, 2)->nullable();
            $table->integer('units')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_movements');
        Schema::dropIfExists('purchase_invoice_items');
        Schema::dropIfExists('purchase_invoices');
        
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['image', 'parent_id']);
        });
    }
};
