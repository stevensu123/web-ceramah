<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        $authorities = config('permission.authorities');

        $listPermission = [];
        $superAdminPermissions = [];
        $adminPermissions = [];
        $userPermissions = [];

        foreach ($authorities as $label => $permissions) {
            foreach ($permissions as $permission) {
                $listPermission[] = [
                    'name' => $permission,
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                // Super admin
                $superAdminPermissions[] = $permission;

                // Admin
                if (in_array($label, ['manage_users', 'manage_quots'])) {
                    $adminPermissions[] = $permission;
                }

                // Editor
                if (in_array($label, ['manage_cerita'])) {
                    $userPermissions[] = $permission;
                }
            }
        }

        // Insert permissions
        Permission::insert($listPermission);

        // Generate descriptions
        $descriptionSuperAdmin = $this->generateDescription(
            $this->getManagePermissions($superAdminPermissions, $authorities),
            'SuperAdmin'
        );

        $descriptionAdmin = $this->generateDescription(
            $this->getManagePermissions($adminPermissions, $authorities),
            'Admin'
        );

        $descriptionUser = $this->generateDescription(
            $this->getManagePermissions($userPermissions, $authorities),
            'User'
        );

        // Insert roles with descriptions
        $superAdmin = Role::create([
            'name' => "SuperAdmin",
            'guard_name' => 'web',
            'description' => $descriptionSuperAdmin,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $admin = Role::create([
            'name' => "Admin",
            'guard_name' => 'web',
            'description' => $descriptionAdmin,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = Role::create([
            'name' => "User",
            'guard_name' => 'web',
            'description' => $descriptionUser,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $superAdmin->givePermissionTo($superAdminPermissions);
        $admin->givePermissionTo($adminPermissions);
        $user->givePermissionTo($userPermissions);
    }

    

    private function getManagePermissions(array $permissions, array $authorities): array
    {
        $managePermissions = [];
        foreach ($authorities as $manageName => $availablePermissions) {
            if (array_intersect($permissions, $availablePermissions)) {
                $managePermissions[] = $manageName;
            }
        }
        return $managePermissions;
    }

    private function generateDescription(array $managePermissions, string $roleName): string
    {
        if (count($managePermissions) > 0) {
            if (count($managePermissions) == 1) {
                return 'Control ' . $managePermissions[0] . ' manage permission for ' . $roleName;
            } elseif (count($managePermissions) == 2) {
                return 'Control ' . implode(' and ', $managePermissions) . ' manage permissions for ' . $roleName;
            } else {
                $last = array_pop($managePermissions);
                return 'Control ' . implode(', ', $managePermissions) . ' and ' . $last . ' manage permissions for ' . $roleName;
            }
        } else {
            return 'Create story access only for ' . $roleName;
        }
    }
}
