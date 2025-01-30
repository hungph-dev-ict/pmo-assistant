<?php

namespace App\Services;

use App\Models\Task;

class TaskService
{
    /**
     * Get task tree by project ID.
     *
     * @param int $projectId
     * @return array
     */
    public function getTaskTreeByProject($projectId)
    {
        // Lấy tất cả tasks của project
        $tasks = Task::where('project_id', $projectId)
            ->with(['taskStatus', 'taskPriority', 'children', 'assigneeUser'])
            ->get();

        // Tạo cấu trúc tree
        $taskTree = $tasks
            ->whereNull('parent_id') // Chỉ lấy những task không có parent (epics)
            ->map(function ($epic) {
                return [
                    'id' => $epic->id,
                    'name' => $epic->name,
                    'type' => $epic->type === 0 ? 'epic' : 'task',
                    'priority' => $epic->taskPriority->value1 ?? 'N/A',
                    'assignee' => $epic->assigneeUser ? $epic->assigneeUser : 'N/A',
                    'status' => $epic->taskStatus->value1 ?? 'N/A', // Thêm check cho status
                    'plan_start_date' => $epic->plan_start_date,
                    'plan_end_date' => $epic->plan_end_date,
                    'actual_start_date' => $epic->plan_start_date,
                    'actual_end_date' => $epic->plan_end_date,
                    'estimate_effort' => $epic->estimate_effort,
                    'actual_effort' => $epic->estimate_effort,
                    'children' => $epic->children->map(function ($task) {
                        return [
                            'id' => $task->id,
                            'name' => $task->name,
                            'type' => $task->type === 0 ? 'epic' : 'task',
                            'priority' => $task->taskPriority->value1 ?? 'N/A',
                            'assignee' => $task->assigneeUser ? $task->assigneeUser : 'N/A',
                            'status' => $task->taskStatus->value1 ?? 'N/A', // Thêm check cho status
                            'plan_start_date' => $task->plan_start_date,
                            'plan_end_date' => $task->plan_end_date,
                            'actual_start_date' => $task->plan_start_date,
                            'actual_end_date' => $task->plan_end_date,
                            'estimate_effort' => $task->estimate_effort,
                            'actual_effort' => $task->estimate_effort,
                        ];
                    }),
                ];
            })
            ->toArray();

        return $taskTree;
    }

    public function getTasksByProject($projectId)
    {
        $tasks = Task::where('project_id', $projectId)
        ->with(['taskStatus', 'taskPriority', 'assigneeUser']) // Gọi các quan hệ cần thiết
        ->orderByRaw('COALESCE(parent_id, id), parent_id IS NOT NULL, id') // Sắp xếp theo Epic và Task con
        ->get()
        ->map(function ($task, $index) {
            return [
                'id' => $task->id,
                'name' => $task->name,
                'type' => $task->parent_id ? 'task' : 'epic', // Xác định loại (Epic hoặc Task)
                'priority' => $task->taskPriority->value1 ?? 'N/A',
                'assignee' => $task->assigneeUser ? $task->assigneeUser : 'N/A',
                'status' => $task->taskStatus->value1 ?? 'N/A',
                'plan_start_date' => $task->plan_start_date,
                'plan_end_date' => $task->plan_end_date,
                'actual_start_date' => $task->actual_start_date,
                'actual_end_date' => $task->actual_end_date,
                'estimate_effort' => $task->estimate_effort,
                'actual_effort' => $task->actual_effort,
                'display_order' => $index + 1 // Thêm cột display_order bắt đầu từ 1
            ];
        });
        
        return $tasks;
    }

    public function getOwnTasksByProject($projectId)
    {
        $ownTasks = Task::where('project_id', $projectId)
        ->where('assignee', auth()->id())
        ->with(['taskStatus', 'taskPriority', 'assigneeUser']) // Gọi các quan hệ cần thiết
        ->orderByRaw('COALESCE(parent_id, id), parent_id IS NOT NULL, id') // Sắp xếp theo Epic và Task con
        ->get()
        ->map(function ($task, $index) {
            return [
                'id' => $task->id,
                'name' => $task->name,
                'type' => $task->parent_id ? 'task' : 'epic', // Xác định loại (Epic hoặc Task)
                'priority' => $task->taskPriority->value1 ?? 'N/A',
                'assignee' => $task->assigneeUser ? $task->assigneeUser : 'N/A',
                'status' => $task->taskStatus->value1 ?? 'N/A',
                'plan_start_date' => $task->plan_start_date,
                'plan_end_date' => $task->plan_end_date,
                'actual_start_date' => $task->actual_start_date,
                'actual_end_date' => $task->actual_end_date,
                'estimate_effort' => $task->estimate_effort,
                'actual_effort' => $task->actual_effort,
                'display_order' => $index + 1 // Thêm cột display_order bắt đầu từ 1
            ];
        });
        
        return $ownTasks;
    }
}
