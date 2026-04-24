<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SmartBalance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Initial Manager
        $admin = User::firstOrCreate(
            ['phone' => '07742209251'],
            [
                'name' => 'المدير العام',
                'password' => Hash::make('12345678'),
                'role' => 'manager',
                'status' => 'active',
                'salary_amount' => 0,
                'salary_due_day' => 30,
            ]
        );

        // 2. Initialize Balance for Admin if it doesn't exist
        if ($admin->balances()->count() === 0) {
            $admin->balances()->create([
                'currency' => 'IQD',
                'balance' => 0,
                'last_transaction_at' => now(),
            ]);
            $admin = User::firstOrCreate(
                ['phone' => '07742209252'],
                [
                    'name' => 'المدير العام2',
                    'password' => Hash::make('12345678'),
                    'role' => 'manager',
                    'status' => 'active',
                    'salary_amount' => 0,
                    'salary_due_day' => 30,
                ]
            );

            // 2. Initialize Balance for Admin if it doesn't exist
            if ($admin->balances()->count() === 0) {
                $admin->balances()->create([
                    'currency' => 'IQD',
                    'balance' => 0,
                    'last_transaction_at' => now(),
                ]);
            }
        }
    }
}
