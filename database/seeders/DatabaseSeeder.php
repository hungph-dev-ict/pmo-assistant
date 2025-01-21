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
        DB::table('projects')->truncate();
        DB::table('project_user')->truncate();
        User::truncate();
        Permission::truncate();
        DB::table('constants')->truncate();
        Role::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call([
            ConstantsTableSeeder::class,
            RolesAndPermissionsSeeder::class,
            UserSeeder::class
        ]);

       
    }
}
