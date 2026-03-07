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
        if (!Schema::hasColumn('users', 'total_gift_points')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('total_gift_points')->default(0)->after('email');
            });
        }

        if (!Schema::hasColumn('representatives', 'total_gift_points')) {
            Schema::table('representatives', function (Blueprint $table) {
                $table->integer('total_gift_points')->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('total_gift_points');
        });

        Schema::table('representatives', function (Blueprint $table) {
            $table->dropColumn('total_gift_points');
        });
    }
};
