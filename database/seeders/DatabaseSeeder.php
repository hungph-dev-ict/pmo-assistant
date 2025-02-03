<?php

namespace Database\Seeders;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Danh sách các bảng cần truncate
        $tables = [
            'plans',
            'tasks',
            'projects',
            'project_user',
            'tenants',
            'constants',
            'model_has_roles',       // Bảng quan hệ giữa user và role
            'role_has_permissions',  // Bảng quan hệ giữa role và permission
            'model_has_permissions', // Bảng quan hệ giữa user và permission
        ];

        // Xóa dữ liệu tất cả các bảng trong danh sách
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        // Xóa dữ liệu của các Model liên quan (Eloquent)
        foreach ([User::class, Role::class, Permission::class] as $model) {
            $model::truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call([
            ConstantsTableSeeder::class,
            RolesAndPermissionsSeeder::class,
            PlansTableSeeder::class,
            TenantsTableSeeder::class,
            UserTableSeeder::class,
            ProjectsTableSeeder::class,
            TasksTableSeeder::class,
        ]);

        DB::table('project_user')->insert([
            [
                'project_id' => '1',
                'user_id' => '3',
            ],
            [
                'project_id' => '1',
                'user_id' => '4',
            ],
            [
                'project_id' => '1',
                'user_id' => '5',
            ],
            [
                'project_id' => '2',
                'user_id' => '3',
            ],
            [
                'project_id' => '2',
                'user_id' => '4',
            ],
            [
                'project_id' => '2',
                'user_id' => '5',
            ],
            [
                'project_id' => '3',
                'user_id' => '3',
            ],
            [
                'project_id' => '3',
                'user_id' => '4',
            ],
            [
                'project_id' => '3',
                'user_id' => '5',
            ],
        ]);
    }
}
