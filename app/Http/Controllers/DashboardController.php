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
            ->get();

        $criticalTasks = Task::where(function ($query) {
            $query->whereDate('plan_end_date', '<', now()) // Overdue
                ->orWhereDate('plan_start_date', '<', now()->subDays(1)) // Start late
                ->orWhereColumn('actual_effort', '>', 'estimate_effort'); // Overcost
        })->get()->map(function ($task) {
            if ($task->plan_end_date < now()) {
                $task->alert_type = 'Overdue';
            } elseif ($task->plan_start_date < now()->subDays(1)) {
                $task->alert_type = 'DelayedStart';
            } elseif ($task->actual_effort > $task->estimate_effort) {
                $task->alert_type = 'Overcost';
            }
            return $task;
        });

        return view('dashboard', compact('user_count', 'project_count', 'incompleteTasks', 'criticalTasks'));
    }
}
