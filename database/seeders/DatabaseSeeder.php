<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
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
        User::truncate();
        Permission::truncate();
        Role::truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call([
            ConstantsTableSeeder::class,
            RolesAndPermissionsSeeder::class,
        ]);

        User::factory(10)->create();

        User::factory()->create([
            'account' => 'ROOT',
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'job_position' => '1',
            'status' => '1',
            'password' => bcrypt('admin@123') // Mã hóa mật khẩu
        ]);

        User::factory()->create([
            'account' => 'PMO',
            'name' => 'PMO Account',
            'email' => 'pm@gmail.com',
            'job_position' => '1',
            'status' => '1',
            'password' => bcrypt('pmo@12345') // Mã hóa mật khẩu
        ]);

        User::factory()->create([
            'account' => 'DEV',
            'name' => 'Dev Account',
            'email' => 'dev@gmail.com',
            'job_position' => '3',
            'status' => '1',
            'password' => bcrypt('dev@12345') // Mã hóa mật khẩu
        ]);

        User::factory()->create([
            'account' => 'TEST',
            'name' => 'Test Account',
            'email' => 'test@gmail.com',
            'job_position' => '4',
            'status' => '1',
            'password' => bcrypt('test@12345') // Mã hóa mật khẩu
        ]);
        Project::factory(100)->create();


        //Gán vai trò admin cho user có email admin@gmail.com
        $admin = User::where('email', 'admin@gmail.com')->first();
        if ($admin) {
            $admin->assignRole('admin');
        }

        // Gán vai trò pm cho user có email pm@gmail.com
        $pm = User::where('email', 'pm@gmail.com')->first();
        if ($pm) {
            $pm->assignRole('pm');
        }

        // Gán vai trò user cho user có email dev@gmail.com
        $dev = User::where('email', 'dev@gmail.com')->first();
        if ($dev) {
            $dev->assignRole('user');
        }

        // Gán vai trò user cho user có email test@gmail.com
        $test = User::where('email', 'test@gmail.com')->first();
        if ($test) {
            $test->assignRole('user');
        }
    }
}
