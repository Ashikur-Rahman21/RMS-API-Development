<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create-roles' => 'Roles Management',
            'read-roles' => 'Roles Management',
            'update-roles' => 'Roles Management',
            'delete-roles' => 'Roles Management',           
            
            'create-users' => 'User Management',
            'read-users' => 'User Management',
            'update-users' => 'User Management',
            'delete-users' => 'User Management',
        ];
        foreach($permissions as $permission => $category){
            Permission::create([
                'name' => $permission,
                'guard_name' => 'sanctum',
                'category' => $category
            ]);
        }
    }
}
