<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;
use Hash;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'user_type' => 'admin',
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'phone' => '012578557878',
            'password' => Hash::make('123456')
        ]);
        $role = Role::create([
            'name' => 'Admin',
            'guard_name'=>'sanctum'
        ]);
        $permissions = Permission::pluck('id','id')->all();
        $role->syncPermissions($permissions);
        $admin->assignRole($role);
    }
}
