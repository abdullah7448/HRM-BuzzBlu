<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create the core roles (using firstOrCreate to avoid duplicate errors)
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $hrRole = Role::firstOrCreate(['name' => 'HR']);
        $deptHeadRole = Role::firstOrCreate(['name' => 'Department Head']);
        $employeeRole = Role::firstOrCreate(['name' => 'Employee']);

        $defaultPassword = Hash::make('password');

        // 1. Create Super Admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@hrm.com'],
            ['name' => 'System Admin', 'password' => $defaultPassword]
        );
        $adminUser->assignRole($superAdminRole);

        // 2. Create HR User
        $hrUser = User::firstOrCreate(
            ['email' => 'hr@hrm.com'],
            ['name' => 'HR Manager', 'password' => $defaultPassword]
        );
        $hrUser->assignRole($hrRole);

        // 3. Create Department Head User
        $deptHeadUser = User::firstOrCreate(
            ['email' => 'head@hrm.com'],
            ['name' => 'Marketing Head', 'password' => $defaultPassword]
        );
        $deptHeadUser->assignRole($deptHeadRole);

        // 4. Create Employee User
        $employeeUser = User::firstOrCreate(
            ['email' => 'employee@hrm.com'],
            ['name' => 'General Employee', 'password' => $defaultPassword]
        );
        $employeeUser->assignRole($employeeRole);
    }
}