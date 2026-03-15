<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            'view dashboard',
            'view admins',
            'create admins',
            'edit admins',
            'disable admins',
            'view plans',
            'create plans',
            'edit plans',
            'disable plans',
            'view plan features',
            'edit plan features',
            'view subscribers',
            'create subscribers',
            'edit subscribers',
            'disable subscribers',
            'view stores',
            'edit stores',
            'view subscriptions',
            'create subscriptions',
            'edit subscriptions',
            'suspend subscriptions',
            'view payments',
            'create payments',
            'review payments',
            'view usage',
            'manage usage overrides',
            'manage roles',
            'manage settings',
            'view reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'admin']);
        }

        // Create Roles and Assign Permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'admin']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'admin']);
        $supportRole = Role::firstOrCreate(['name' => 'Support', 'guard_name' => 'admin']);
        $viewerRole = Role::firstOrCreate(['name' => 'Viewer', 'guard_name' => 'admin']);

        // Super Admin gets all permissions
        $superAdminRole->givePermissionTo(Permission::all());

        // Admin gets some permissions
        $adminRole->givePermissionTo(['view dashboard', 'manage admins', 'manage settings']);

        // Support gets limited permissions
        $supportRole->givePermissionTo(['view dashboard', 'view reports']);

        // Viewer gets only view dashboard
        $viewerRole->givePermissionTo(['view dashboard']);

        // Create Default Super Admin
        $superAdmin = Admin::firstOrCreate(
        ['email' => 'admin@dokkan.com'],
        [
            'name' => 'Super Admin',
            'password' => Hash::make('12345678'), // Change this in production
            'status' => 'active',
            'email_verified_at' => now(),
        ]
        );

        $superAdmin->assignRole($superAdminRole);

        $this->command->info('Admin system seeded successfully!');
        $this->command->info('Super Admin Email: admin@dokkan.com');
        $this->command->info('Super Admin Password: 12345678');
    }
}
