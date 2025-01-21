<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true), // Ví dụ: "Project Alpha Beta"
            'description' => $this->faker->sentence(10), // Ví dụ: "A random description for the project."
            'start_date' => $this->faker->dateTimeBetween('-1 year', 'now'), // Ngày bắt đầu ngẫu nhiên trong quá khứ
            'end_date' => $this->faker->dateTimeBetween('now', '+1 year'), // Ngày kết thúc ngẫu nhiên trong tương lai
            'status' => $this->faker->randomElement([1, 2, 3]), // Thay bằng số thay vì chuỗi
            'client_company' => $this->faker->company, // Tên công ty khách hàng ngẫu nhiên
            'project_manager' => $this->faker->randomElement([1, 2, 3]), // Tên người quản lý dự án ngẫu nhiên
            'estimated_budget' => $this->faker->numberBetween(10000, 500000), // Ngân sách 10,000 - 500,000
            'estimated_project_duration' => $this->faker->numberBetween(30, 365), // Thời gian dự án 30 - 365 ngày
        ];
    }
}
