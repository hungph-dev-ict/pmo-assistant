<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'account' => 'ROOT',
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'job_position' => '1',
            'status' => '1',
            'password' => bcrypt('admin@123') // Mã hóa mật khẩu
        ]);

        User::factory()->create([
            'account' => 'CLIENT',
            'name' => 'Client',
            'email' => 'client@gmail.com',
            'job_position' => '1',
            'status' => '1',
            'password' => bcrypt('client@123') // Mã hóa mật khẩu
        ]);

        User::factory()->create([
            'account' => 'PMO',
            'name' => 'PMO Account',
            'email' => 'pm@gmail.com',
            'client_head_id' => '2',
            'job_position' => '1',
            'status' => '1',
            'password' => bcrypt('pm@12345') // Mã hóa mật khẩu
        ]);

        User::factory()->create([
            'account' => 'DEV',
            'name' => 'Dev Account',
            'email' => 'dev@gmail.com',
            'client_head_id' => '2',
            'job_position' => '3',
            'status' => '1',
            'password' => bcrypt('dev@12345') // Mã hóa mật khẩu
        ]);

        User::factory()->create([
            'account' => 'TEST',
            'name' => 'Test Account',
            'email' => 'test@gmail.com',
            'client_head_id' => '2',
            'job_position' => '4',
            'status' => '1',
            'password' => bcrypt('test@12345') // Mã hóa mật khẩu
        ]);

        //Gán vai trò admin cho user có email admin@gmail.com
        $admin = User::where('email', 'admin@gmail.com')->first();
        if ($admin) {
            $admin->assignRole('admin');
        }

         //Gán vai trò admin cho user có email admin@gmail.com
         $admin = User::where('email', 'client@gmail.com')->first();
         if ($admin) {
             $admin->assignRole('client');
         }

        // Gán vai trò pm cho user có email pm@gmail.com
        $pm = User::where('email', 'pm@gmail.com')->first();
        if ($pm) {
            $pm->assignRole('pm');
        }

        //Gán vai trò user cho user có email dev@gmail.com
        $dev = User::where('email', 'dev@gmail.com')->first();
        if ($dev) {
            $dev->assignRole('staff');
        }

        // Gán vai trò user cho user có email test@gmail.com
        $test = User::where('email', 'test@gmail.com')->first();
        if ($test) {
            $test->assignRole('staff');
        }
    }
}
