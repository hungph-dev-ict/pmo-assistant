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
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'manage projects']);
        Permission::firstOrCreate(['name' => 'view tasks']);
        Permission::firstOrCreate(['name' => 'update tasks']);

        // Tạo vai trò Admin và gán tất cả quyền
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        // Tạo vai trò PM và gán quyền liên quan đến dự án
        $pmRole = Role::firstOrCreate(['name' => 'pm']);
        $pmRole->givePermissionTo(['manage projects']);

        // Tạo vai trò User và gán quyền liên quan đến task
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $userRole->givePermissionTo(['view tasks', 'update tasks']);
    }
}
