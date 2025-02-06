<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\TaskService;
use App\Http\Requests\StoreTaskRequest;


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

    public function store(Request $request, $project_id)
    {
        // dd($request->all());
        $newTaskInfo = $request->all();
        $createNewTask = $this->taskService->createTask($newTaskInfo);
        // if ($createNewTask) {
        //     return redirect()->route('pm.tasks.store')
        //         ->with('success', 'Task created successfully.');
        // }

        // return 500;
    }
}
