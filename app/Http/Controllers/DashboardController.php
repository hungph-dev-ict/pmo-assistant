<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;
        $userId = Auth::id();

        // Đếm số lượng user trong công ty
        $user_count = User::where('tenant_id', $tenantId)->count();

        // Đếm số lượng project của tenant hiện tại
        $project_count = Project::whereHas('projectManager', function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId);
        })->count();

        // Query chung cho các task thuộc công ty
        $taskQuery = Task::whereHas('project.projectManager', function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId);
        });

        // Đếm số lượng task đã hoàn thành (status = 1) của toàn bộ công ty
        $task_done_count = (clone $taskQuery)->where('status', 1)->count();

        // Đếm số lượng task đã hoàn thành của user đang đăng nhập
        $user_task_done_count = (clone $taskQuery)->where('status', 1)
            ->where('assignee', Auth::id())
            ->count();

        // Tính tỷ lệ đóng góp (làm tròn lên)
        $contribution_rate = $task_done_count > 0 ? ceil(($user_task_done_count / $task_done_count) * 100) : 0;

        // Lấy tất cả task chưa "Done" của user đang đăng nhập
        $incompleteTasks = Task::where('assignee', $userId)
            ->with('project:id,name')
            ->whereNotIn('status', [4, 7])
            ->orderBy('priority', 'desc')
            ->paginate(10, ['*'], 'it_page')->through(function ($task) {
                if ($task->plan_end_date < now()) {
                    $task->overdue = true;
                }

                if ($task->plan_start_date < now()->subDays(1) && $task->status == 0) {
                    $task->delayed = true;
                }
                return $task;
            });

        $criticalTasks = Task::where('assignee', $userId)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereDate('plan_end_date', '<', now()) // Overdue
                        ->whereNotIn('status', [4, 7]);
                })->orWhere(function ($q) {
                    $q->whereDate('plan_start_date', '<', now()->subDays(1)) // Start late
                        ->where('status', 0);
                })->orWhereColumn('actual_effort', '>', 'plan_effort'); // Overcost
            })
            ->with(['project:id,name', 'taskStatus', 'taskPriority'])
            ->orderBy('priority', 'desc')
            ->paginate(10, ['*'], 'ct_page')
            ->through(function ($task) {
                $task->overdue = $task->plan_end_date < now() && !in_array($task->status, [4, 7]);
                $task->delayed = $task->plan_start_date < now()->subDays(1) && $task->status == 0;
                $task->overcost = floatval($task->actual_effort) > floatval($task->plan_effort);

                return $task;
            });


        return view('dashboard', compact('user_count', 'project_count', 'incompleteTasks', 'criticalTasks', 'task_done_count', 'contribution_rate'));
    }
}
