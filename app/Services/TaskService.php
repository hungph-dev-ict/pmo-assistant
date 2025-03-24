<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\Constant;
use Illuminate\Support\Facades\Log;

class TaskService
{
    public function getTasksByFilter($projectId, $request)
    {
        $query = Task::where('project_id', $projectId)
            ->with(['taskStatus', 'taskPriority', 'assigneeUser:id,account'])
            ->orderByRaw('COALESCE(parent_id, id), parent_id IS NOT NULL, id');

        // Lọc theo text
        if ($request->filled('text')) {
            $f_title = $request->input('text', ''); // Nếu không có text thì gán rỗng
            $query->where('name', 'LIKE', "%$f_title%");
        }

        if ($request->filled('priority')) {
            // Chuyển chuỗi priority thành mảng
            $f_priorityArray = explode(',', $request->priority);

            // Truy vấn bảng constants để lấy các priority tương ứng trong tasks
            $priorityMapped = Constant::where('group', 'task_priority')
                ->whereIn('value1', $f_priorityArray)
                ->pluck('key') // hoặc 'id' tùy vào cách ánh xạ của bạn
                ->toArray();

            // Nếu tìm thấy priority hợp lệ, áp dụng vào query
            if (!empty($priorityMapped)) {
                $query->whereIn('priority', $priorityMapped);
            }
        }

        if ($request->filled('assignee')) {
            $f_assignee = $request->assignee; // account của user
            $project = Project::find($projectId);
            $tenantId = $project->projectManager->tenant_id;
            // 🔹 Lấy user_id từ bảng users dựa trên account
            $user = User::where('account', $f_assignee)->where('tenant_id', $tenantId)->first();

            if ($user) {
                $query->where('assignee', $user->id); // Lọc theo ID của user
            } else {
                // Không tìm thấy user nào -> không trả về kết quả
                $query->whereRaw('0 = 1');
            }
        }

        // Lọc theo status (nếu có)
        if ($request->filled('status')) {
            // Chuyển chuỗi status thành mảng
            $f_statusArray = explode(',', $request->status);

            // Truy vấn bảng constants để lấy các status tương ứng trong tasks
            $statusMapped = Constant::where('group', 'task_status')
                ->whereIn('value1', $f_statusArray)
                ->pluck('key') // hoặc 'id' tùy vào cách ánh xạ của bạn
                ->toArray();

            // Nếu tìm thấy status hợp lệ, áp dụng vào query
            if (!empty($statusMapped)) {
                $query->whereIn('status', $statusMapped);
            }
        }
        // Log::debug("Generated SQL: " . $query->toSql());
        // Log::debug("Bindings: ", $query->getBindings());

        $task_list = $query->get()->map(function ($task) {
            $task->overdue = !is_null($task->plan_end_date) && $task->plan_end_date < now() && !in_array($task->status, [4, 7]);
            $task->delayed = !is_null($task->plan_start_date) && $task->plan_start_date < now()->subDays(1) && $task->status == 0;
            $task->overcost = floatval($task->actual_effort) > floatval($task->plan_effort);

            return $task;
        });

        // Lấy danh sách assignee duy nhất từ danh sách task
        $assignees = $task_list->pluck('assignee:id,account')->filter()->unique('id')->values();

        // Áp dụng lọc dựa trên các biến truyền vào
        if ($request->boolean('checkDelayed')) {
            $task_list = $task_list->where('delayed', true);
        }

        if ($request->boolean('checkOverDue')) {
            $task_list = $task_list->where('overdue', true);
        }

        if ($request->boolean('checkOverCost')) {
            $task_list = $task_list->where('overcost', true);
        }

        $priorities = Constant::getPriorityList();
        Log::info($task_list);

        return [
            'tasks' => $task_list->values()->toArray(),
            'assignees' => $assignees,
            'priorities' => $priorities,
        ];
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
                    'plan_effort' => $task->plan_effort,
                    'actual_effort' => $task->actual_effort,
                    'display_order' => $index + 1 // Thêm cột display_order bắt đầu từ 1
                ];
            });

        return $ownTasks;
    }
}
