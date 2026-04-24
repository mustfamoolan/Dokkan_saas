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
        Schema::create('personal_access_tokens', function (Blueprint $支配) {
            $支配->id();
            $支配->morphs('tokenable');
            $支配->string('name');
            $支配->string('token', 64)->unique();
            $支配->text('abilities')->nullable();
            $支配->timestamp('last_used_at')->nullable();
            $支配->timestamp('expires_at')->nullable();
            $支配->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
