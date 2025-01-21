<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('projects')->insert([
            [
                'client_company' => 'ABC Corp',
                'created_at' => Carbon::now(),
                'description' => 'Project for ABC Corp to develop a new software solution.',
                'end_date' => Carbon::parse('2025-12-31'),
                'estimated_budget' => 500000,
                'estimated_project_duration' => 12,
                'name' => 'Software Development for ABC',
                'project_manager' => 3,
                'start_date' => Carbon::parse('2025-01-01'),
                'status' => 1,
                'updated_at' => Carbon::now(),
            ],
            [
                'client_company' => 'XYZ Ltd',
                'created_at' => Carbon::now(),
                'description' => 'Development of a mobile application for XYZ Ltd.',
                'end_date' => Carbon::parse('2024-11-30'),
                'estimated_budget' => 250000,
                'estimated_project_duration' => 6,
                'name' => 'Mobile App for XYZ',
                'project_manager' => 3,
                'start_date' => Carbon::parse('2024-06-01'),
                'status' => 2,
                'updated_at' => Carbon::now(),
            ],
            [
                'client_company' => 'LMN Inc',
                'created_at' => Carbon::now(),
                'description' => 'Project for LMN Inc to improve their website performance.',
                'end_date' => Carbon::parse('2024-09-30'),
                'estimated_budget' => 150000,
                'estimated_project_duration' => 4,
                'name' => 'Website Performance Improvement for LMN',
                'project_manager' => 3,
                'start_date' => Carbon::parse('2024-06-15'),
                'status' => 3,
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
