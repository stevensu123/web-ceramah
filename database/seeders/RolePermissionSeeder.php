<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authorities = config('permission.authorities');

        $listPermission = [];
        $superAdminPermissions = [];
        $adminPermissions = [];
        $userPermissions = [];

        foreach($authorities as $label => $permissions)
        {
            foreach ($permissions as $permission)
            {
                $listPermission[] = [
                    'name' => $permission,
                    'guard_name' => 'web',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                //superadmin
                $superAdminPermissions[] = $permission;
    
                //admin
                if(in_array($label,['manage_users','manage_quots']))
                {
                    $adminPermissions[] = $permission;
                }
    
                //ediotr
                if(in_array($label,['manage_cerita']))
                {
                    $userPermissions[] = $permission;
                }
            }
           
        }

        
        //insert permission
        Permission::insert($listPermission);


        //inser role
        //super admin
        $superAdmin = Role::create([
            'name' => "SuperAdmin",
            'guard_name' => 'web',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        //admin
        $admin = Role::create([
            'name' => "Admin",
            'guard_name' => 'web',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        //editor
        $user = Role::create([
            'name' => "User",
            'guard_name' => 'web',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        //role -> permission
        $superAdmin->givePermissionTo($superAdminPermissions);
        $admin->givePermissionTo($adminPermissions);
        $user->givePermissionTo($userPermissions);

  

    }
}
