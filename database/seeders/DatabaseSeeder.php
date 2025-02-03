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
        // Xóa toàn bộ dữ liệu trước khi seed
        DB::table('plans')->truncate();
        DB::table('tasks')->truncate();
        DB::table('projects')->truncate();
        DB::table('project_user')->truncate();
        User::truncate();
        DB::table('tenants')->truncate();
        Permission::truncate();
        DB::table('constants')->truncate();
        Role::truncate();

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
