<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
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

        $user_count = User::where('tenant_id', Auth::user()->tenant_id)->count();

        // Đếm số lượng project của tenant hiện tại
        $project_count = Project::whereHas('projectManager', function ($query) use ($tenantId) {
            $query->where('tenant_id', $tenantId);
        })->count();

        // Lấy tất cả task chưa "Done" của user đang đăng nhập
        $incompleteTasks = Task::where('assignee', $userId)
            ->where('status', '!=', 'Done')
            ->paginate(10, ['*'], 'it_page')->through(function ($task) {
                if ($task->plan_end_date < now()) {
                    $task->overdue = true;
                }

                if ($task->plan_start_date < now()->subDays(1) && $task->status != 4) {
                    $task->delayed = true;
                }

                if ($task->actual_effort > $task->estimate_effort) {
                    $task->overcost = true;
                };
                return $task;
            });

        $criticalTasks = Task::where(function ($query) use ($userId) {
            $query->where('assignee', $userId)
                ->whereDate('plan_end_date', '<', now()) // Overdue
                ->orWhereDate('plan_start_date', '<', now()->subDays(1)) // Start late
                ->orWhereColumn('actual_effort', '>', 'estimate_effort'); // Overcost
        })->with('taskStatus', 'taskPriority')->paginate(10, ['*'], 'ct_page')->through(function ($task) {
            if ($task->plan_end_date < now()) {
                $task->overdue = true;
            }

            if ($task->plan_start_date < now()->subDays(1) && $task->status != 4) {
                $task->delayed = true;
            }

            if ($task->actual_effort > $task->estimate_effort) {
                $task->overcost = true;
            }
            return $task;
        });

        return view('dashboard', compact('user_count', 'project_count', 'incompleteTasks', 'criticalTasks'));
    }
}
