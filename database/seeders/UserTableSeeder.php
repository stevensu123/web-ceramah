<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $superAdmin = User::create([
            'name' => 'steven',
            'no_hp' => '081271648758',
            'username' => 'superadmin',
            'status' => 'approved',
            'password' => Hash::make('123456'),
        ]);
        $superAdmin->assignRole('SuperAdmin');
      
        $admin = User::create([
            'name' => 'coba',
            'no_hp' => '081271648758',
            'username' => 'admin',
            'status' => 'approved',
            'password' => Hash::make('123456'),
        ]);
        $admin->assignRole('Admin');

        $user = User::create([
            'name' => 'coba1',
            'no_hp' => '081271648758',
            'username' => 'user',
            'status' => 'pending',
            'password' => Hash::make('123456'),
        ]);
        $user->assignRole('User');
    }
}
