<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('smart_balances', function (Blueprint $table) {
            $table->id();
            $table->morphs('balancable'); // balancable_id and balancable_type
            $table->enum('currency', ['USD', 'IQD'])->default('IQD');
            $table->decimal('balance', 18, 2)->default(0); // positive = Credit (له), negative = Debt (عليه)
            $table->timestamp('last_transaction_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('smart_balances');
    }
};
