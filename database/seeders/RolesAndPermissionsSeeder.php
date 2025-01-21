<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo các quyền
        Permission::firstOrCreate(['name' => 'manage clients']);
        Permission::firstOrCreate(['name' => 'manage projects']);
        Permission::firstOrCreate(['name' => 'manage staff']);
        Permission::firstOrCreate(['name' => 'view own project tasks']);
        Permission::firstOrCreate(['name' => 'update own project tasks']);
        Permission::firstOrCreate(['name' => 'view own project members']);
        Permission::firstOrCreate(['name' => 'update own project members']);
        Permission::firstOrCreate(['name' => 'view advanced charts']);
        Permission::firstOrCreate(['name' => 'view own tasks']);
        Permission::firstOrCreate(['name' => 'update own tasks']);
        Permission::firstOrCreate(['name' => 'view basic charts']);

        // Tạo vai trò Admin và gán tất cả quyền
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(['manage clients']);

        // Tạo vai trò Client và gán tất cả quyền
        $adminRole = Role::firstOrCreate(['name' => 'client']);
        $adminRole->givePermissionTo([
            'manage projects',
            'manage staff',
        ]);

        // Tạo vai trò PM và gán quyền liên quan đến dự án
        $pmRole = Role::firstOrCreate(['name' => 'pm']);
        $pmRole->givePermissionTo([
            'view own project tasks',
            'update own project tasks',
            'view own project members',
            'update own project members',
            'view advanced charts'
        ]);

        // Tạo vai trò Staff và gán quyền liên quan đến task
        $userRole = Role::firstOrCreate(['name' => 'staff']);
        $userRole->givePermissionTo([
            'view own tasks',
            'update own tasks',
            'view basic charts'
        ]);
    }
}
