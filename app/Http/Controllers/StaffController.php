<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Services\TaskService;

class StaffController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function listTasks(Request $request)
    {
        $projectId = $request->route('project_id');

        // Tìm project hoặc trả về lỗi 404
        $project = Project::with('tasks')->findOrFail($projectId);

        // Gọi service để lấy task tree
        $taskTree = $this->taskService->getTaskTreeByProject($projectId);

        return view('staff.task', compact('project', 'taskTree'));
    }

    public function listMembers()
    {
        return view('staff.member');
    }


    public function viewChart()
    {
        return view('staff.chart');
    }
}
