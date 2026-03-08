<?php

namespace App\Services\Admin;

use Spatie\Permission\Models\Role;

class RoleService
{
    /**
     * Get all roles
     */
    public function getAllRoles(array $filters = []): \Illuminate\Database\Eloquent\Collection
    {
        $query = Role::with('permissions');

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where('name', 'like', "%{$search}%");
        }

        return $query->latest()->get();
    }

    /**
     * Create a new role
     */
    public function createRole(array $data): Role
    {
        $role = Role::create(['name' => $data['name']]);

        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role->fresh();
    }

    /**
     * Update role
     */
    public function updateRole(Role $role, array $data): Role
    {
        $role->update(['name' => $data['name']]);

        if (isset($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return $role->fresh();
    }

    /**
     * Delete role
     */
    public function deleteRole(Role $role): bool
    {
        return $role->delete();
    }

    /**
     * Assign permission to role
     */
    public function assignPermission(Role $role, string $permissionName): void
    {
        $role->givePermissionTo($permissionName);
    }

    /**
     * Revoke permission from role
     */
    public function revokePermission(Role $role, string $permissionName): void
    {
        $role->revokePermissionTo($permissionName);
    }
}
