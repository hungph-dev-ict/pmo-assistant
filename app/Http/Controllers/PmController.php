<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\TaskService;

class PmController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function listTasks(Request $request, $project_id)
    {
        if ($request->ajax()) {
            $tasks = Task::where('project_id', $project_id)->with('assigneeUser')->get();
            $data = $this->taskService->getTasksByProject($project_id);

            // Debug: Kiểm tra dữ liệu trước khi trả về
            return response()->json($data);
        }

        return view('pm.task', compact('project_id'));
    }


    public function listMembers()
    {
        return view('pm.member');
    }

    public function viewChart()
    {
        return view('pm.chart');
    }
}
