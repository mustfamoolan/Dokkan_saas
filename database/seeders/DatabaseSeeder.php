<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::firstOrCreate(
            ['phone' => '07742209251'],
            [
                'name' => 'Dokkan Admin',
                'email' => 'admin@dokkan.com',
                'password' => Hash::make('12345678'),
            ]
        );

        // Create Admin Role if it doesn't exist
        $role = Role::firstOrCreate(['name' => 'admin']);
        $admin->assignRole($role);
    }
}
