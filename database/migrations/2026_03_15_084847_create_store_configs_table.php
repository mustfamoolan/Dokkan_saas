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
        Schema::create('store_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            
            // Operational
            $table->foreignId('default_warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');
            $table->foreignId('default_cashbox_id')->nullable()->constrained('cashboxes')->onDelete('set null');
            $table->foreignId('default_walk_in_customer_id')->nullable()->constrained('customers')->onDelete('set null');
            $table->boolean('allow_sale_without_customer')->default(true);
            $table->boolean('allow_negative_stock')->default(false);

            // Numbering
            $table->string('sales_prefix')->default('SAL-');
            $table->string('purchase_prefix')->default('PUR-');
            $table->string('customer_payment_prefix')->default('CP-');
            $table->string('supplier_payment_prefix')->default('SP-');

            // Printing
            $table->string('print_header_title')->nullable();
            $table->boolean('show_logo_on_print')->default(true);
            $table->boolean('show_phone_on_print')->default(true);
            $table->boolean('show_address_on_print')->default(true);
            $table->text('print_footer_note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_configs');
    }
};
