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
                'group' => 'job_position',
                'key' => '6',
                'value1' => 'COMTOR',
                'value2' => 'Comtor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'job_position',
                'key' => '7',
                'value1' => 'OTHER',
                'value2' => 'Other',
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
                'value1' => 'Pending',
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
            [
                'group' => 'task_status', 
                'key' => '0',
                'value1' => 'Not Started', // Trạng thái chưa bắt đầu
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'task_status', 
                'key' => '1',
                'value1' => 'In Progress', // Trạng thái đang tiến hành
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'task_status', 
                'key' => '2',
                'value1' => 'Resolved', // Trạng thái đã hoàn thành
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'task_status', 
                'key' => '3',
                'value1' => 'Feedback', // Trạng thái đang tiến hành
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'task_status', 
                'key' => '4',
                'value1' => 'Done', // Trạng thái đã hoàn thành
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'task_status', 
                'key' => '5',
                'value1' => 'Reopen', // Trạng thái đã hoàn thành
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'task_priority', 
                'key' => '0',
                'value1' => 'Pending', // Trạng thái chưa bắt đầu
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'task_priority', 
                'key' => '1',
                'value1' => 'Low', // Trạng thái đang tiến hành
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'task_priority', 
                'key' => '2',
                'value1' => 'Medium', // Trạng thái đã hoàn thành
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'task_priority', 
                'key' => '3',
                'value1' => 'High', // Trạng thái đang tiến hành
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'task_priority', 
                'key' => '4',
                'value1' => 'Critical', // Trạng thái đã hoàn thành
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'leave_request_type', 
                'key' => '0',
                'value1' => 'Working from home', // Trạng thái đã hoàn thành
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'leave_request_type', 
                'key' => '1',
                'value1' => 'All-day leave', // Trạng thái đang tiến hành
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'leave_request_type', 
                'key' => '2',
                'value1' => 'Partial leave', // Trạng thái đã hoàn thành
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'leave_request_status', 
                'key' => '0',
                'value1' => 'Pending', // Trạng thái đã hoàn thành
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'leave_request_status', 
                'key' => '1',
                'value1' => 'Approved', // Trạng thái đang tiến hành
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'group' => 'leave_request_status', 
                'key' => '2',
                'value1' => 'Rejected', // Trạng thái đã hoàn thành
                'value2' => NULL,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
