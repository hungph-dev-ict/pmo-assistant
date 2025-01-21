<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConstantsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('constants')->insert([
            [
                'group' => 'job_position',
                'key' => '1',
                'value1' => 'Manager',
                'value2' => 'Head Account',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'job_position',
                'key' => '2',
                'value1' => 'PM',
                'value2' => 'Project Manager',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'job_position',
                'key' => '3',
                'value1' => 'BrSE',
                'value2' => 'Bridge System Engineer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'job_position',
                'key' => '4',
                'value1' => 'DEV',
                'value2' => 'Developer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'job_position',
                'key' => '5',
                'value1' => 'TESTER',
                'value2' => 'Tester',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'user_status',
                'key' => '0',
                'value1' => 'Inactive',
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'user_status',
                'key' => '1',
                'value1' => 'Active',
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'project_status',
                'key' => '0',
                'value1' => 'Pre-contract',
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'project_status',
                'key' => '1',
                'value1' => 'Running',
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'project_status',
                'key' => '2',
                'value1' => 'On hold',
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'project_status',
                'key' => '3',
                'value1' => 'Success',
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'project_status',
                'key' => '4',
                'value1' => 'Canceled',
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
