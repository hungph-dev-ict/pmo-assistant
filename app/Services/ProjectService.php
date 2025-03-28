<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Task;
use App\Enums\TaskStatus;

class ProjectService
{
    public function deleteProjectById(string $id): bool
    {
        $project = Project::findOrFail($id); // Tìm project theo ID, nếu không có sẽ trả về lỗi 404
        return $project->delete(); // Xóa mềm project
    }

    /**
     * Restore a soft-deleted project by its ID.
     */
    public function restoreProjectById(string $id): bool
    {
        $project = Project::withTrashed()->findOrFail($id); // Bao gồm cả project đã bị xóa mềm
        return $project->restore(); // Khôi phục project
    }

    public function createProject(array $data)
    {
        return Project::createProject($data);
    }

    public function updateProject($idProject, array $data)
    {
        $updateProject = Project::updateProject($idProject, $data);

        return $updateProject;
    }

    public function calculateProjectMetrics($project_id, $date)
    {
        $excluded_statuses = [TaskStatus::PENDING, TaskStatus::CANCELED];

        // Hàm chuyển đổi đơn vị
        $convertToMD = fn($hours) => round($hours / 8, 2);
        $convertToMM = fn($hours) => round($hours / 176, 2);

        // Lấy dữ liệu tổng effort
        $reported_plan_effort_hours = Task::where('project_id', $project_id)->sum('plan_effort');
        $reported_actual_effort_hours = Task::where('project_id', $project_id)->sum('actual_effort');

        // Các chỉ số khác chỉ cần đơn vị Hours
        $planned_value = Task::where('project_id', $project_id)
            ->where('plan_end_date', '<=', $date)
            ->whereNotIn('status', $excluded_statuses)
            ->sum('plan_effort');

        $actual_cost = Task::where('project_id', $project_id)
            ->where('actual_end_date', '<=', $date)
            ->whereNotIn('status', $excluded_statuses)
            ->sum('actual_effort');

        $earned_value = Task::where('project_id', $project_id)
            ->where('status', TaskStatus::DONE)
            ->where('actual_end_date', '<=', $date)
            ->whereNotIn('status', $excluded_statuses)
            ->sum('plan_effort');

        $spi = ($planned_value > 0) ? round($earned_value / $planned_value, 2) : 0;
        $cpi = ($actual_cost > 0) ? round($earned_value / $actual_cost, 2) : 0;

        $sv = round($earned_value - $planned_value, 2);
        $cv = round($earned_value - $actual_cost, 2);
        $bac = Task::where('project_id', $project_id)->sum('plan_effort');
        $eac = ($cpi > 0) ? round($bac / $cpi, 2) : $bac;
        $vac = round($bac - $eac, 2);
        $etc = round($eac - $actual_cost, 2);
        $tcpi = ($bac - $earned_value > 0 && $bac - $actual_cost > 0)
            ? round(($bac - $earned_value) / ($bac - $actual_cost), 2)
            : 0;

        return [
            // Chỉ số có 3 đơn vị
            'reported_plan_effort' => [
                'hours' => round($reported_plan_effort_hours, 2),
                'md' => $convertToMD($reported_plan_effort_hours),
                'mm' => $convertToMM($reported_plan_effort_hours),
            ],
            'reported_actual_effort' => [
                'hours' => round($reported_actual_effort_hours, 2),
                'md' => $convertToMD($reported_actual_effort_hours),
                'mm' => $convertToMM($reported_actual_effort_hours),
            ],
            // Các chỉ số khác chỉ hiển thị theo giờ
            'planned_value' => round($planned_value, 2),
            'actual_cost' => round($actual_cost, 2),
            'earned_value' => round($earned_value, 2),
            'spi' => $spi,
            'cpi' => $cpi,
            'sv' => $sv,
            'cv' => $cv,
            'bac' => round($bac, 2),
            'eac' => round($eac, 2),
            'vac' => round($vac, 2),
            'etc' => round($etc, 2),
            'tcpi' => $tcpi,
        ];
    }
}
