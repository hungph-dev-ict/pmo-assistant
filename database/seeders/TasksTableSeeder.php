<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($project_id = 1; $project_id <= 2; $project_id++) {
            // Dummy data for Epics and Tasks
            $epics = [];
            $tasks = [];

            // Create 10 Epics
            for ($i = 1; $i <= 3; $i++) {
                $epics[] = [
                    'type' => 0, // Epic
                    'project_id' => $project_id,
                    'assignee' => rand(3, 5), // Random 4 or 5
                    'name' => 'Epic ' . $i,
                    'description' => 'This is a description for Epic ' . $i,
                    'plan_start_date' => now()->subDays(rand(10, 20)), // Random start date
                    'plan_end_date' => now()->addDays(rand(10, 20)),   // Random end date
                    'actual_start_date' => now()->subDays(rand(5, 10)),
                    'actual_end_date' => now()->addDays(rand(10, 15)),
                    'plan_effort' => rand(20, 50), // Random MD
                    'actual_effort' => rand(20, 50),  // Random MD
                    'progress' => rand(0, 100), // Random progress
                    'priority' => rand(0, 4), // 1: High, 2: Medium, 3: Low
                    'status' => rand(0, 4),   // 0: Open, 1: In Progress, etc.
                    'created_by' => 1, // Assume admin user
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Insert Epics into database and get their IDs
            DB::table('tasks')->insert($epics);
            $epicIds = DB::table('tasks')->where('project_id', $project_id)->where('type', 0)->pluck('id')->toArray();

            // Create 50 Tasks, assigning them to random Epics
            for ($i = 1; $i <= 20; $i++) {
                $tasks[] = [
                    'type' => 1, // Task
                    'project_id' => $project_id,
                    'assignee' => rand(3, 5), // Random 4 or 5
                    'name' => 'Task ' . $i,
                    'description' => 'This is a description for Task ' . $i,
                    'parent_id' => $epicIds[array_rand($epicIds)], // Assign to random Epic
                    'plan_start_date' => now()->subDays(rand(1, 10)), // Random start date
                    'plan_end_date' => now()->addDays(rand(1, 10)),   // Random end date
                    'actual_start_date' => now()->subDays(rand(1, 5)),
                    'actual_end_date' => now()->addDays(rand(5, 15)),
                    'plan_effort' => rand(1, 10), // Random MD
                    'actual_effort' => rand(1, 10),  // Random MD
                    'progress' => rand(0, 100), // Random progress
                    'priority' => rand(1, 3), // 1: High, 2: Medium, 3: Low
                    'status' => rand(0, 4),   // 0: Open, 1: In Progress, etc.
                    'created_by' => 1, // Assume admin user
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Insert Tasks into the database
            DB::table('tasks')->insert($tasks);
        }
    }
}
