<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_boxes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('balance', 18, 2)->default(0);
            $table->string('currency')->default('IQD');
            $table->string('type')->default('main'); // main, drawer, bank
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('financial_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // income, expense
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('financial_box_id')->constrained('financial_boxes');
            $table->foreignId('financial_category_id')->nullable()->constrained('financial_categories');
            $table->string('type'); // IN, OUT, TRANSFER
            $table->decimal('amount', 18, 2);
            $table->string('related_type')->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            $table->text('description')->nullable();
            $table->decimal('balance_after', 18, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
        Schema::dropIfExists('financial_categories');
        Schema::dropIfExists('financial_boxes');
    }
};
