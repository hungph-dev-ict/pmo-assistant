<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
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

            return response()->json($data);
        }
        $listAssignee = Project::with('users')->find($project_id)->users;

        return view('pm.task', compact('project_id', 'listAssignee'));
    }

    public function storeTask(Request $request, $project_id)
    {
        try {
            // Chuyển đổi type từ string thành integer
            $typeMapping = [
                'epic' => 0,
                'task' => 1,
            ];

            // Validate dữ liệu đầu vào
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string|in:epic,task',
                'parent_id' => 'nullable|exists:tasks,id',
                'assignee' => 'nullable|integer|exists:users,id',
                'priority' => 'required|integer|min:0|max:4',
                'estimate_effort' => 'required|numeric|min:0',
                'actual_effort' => 'nullable|numeric|min:0',
                'plan_start_date' => 'nullable|date',
                'plan_end_date' => 'nullable|date|after_or_equal:plan_start_date',
                'actual_start_date' => 'nullable|date',
                'actual_end_date' => 'nullable|date|after_or_equal:actual_start_date',
                'status' => 'required|integer',
                'progress' => 'required|integer|min:0|max:100',
                'created_by' => 'required|exists:users,id',
            ]);

            // Chuyển đổi type từ string sang integer
            $validatedData['type'] = $typeMapping[$validatedData['type']];

            // Thêm project_id vào dữ liệu đã validate
            $validatedData['project_id'] = $project_id;

            // Tạo task mới
            $task = Task::create($validatedData);

            return response()->json([
                'message' => 'Task created successfully!',
                'task' => $task,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create task!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function listEpics($project_id)
    {
        $data = Task::where('project_id', $project_id)->where('type', '0')->get();

        return response()->json($data);
    }

    public function listMembers()
    {
        return view('pm.member');
    }

    public function viewChart($projectId)
    {
        // Lấy tất cả tasks thuộc dự án, bao gồm cả epic (cha) và task con
        $tasks = Task::where('project_id', $projectId)->get();

        // Tạo cấu trúc cây với epic làm cha
        $taskTree = $tasks->whereNull('parent_id')->map(function ($epic) {
            return [
                'name' => $epic->name, // Epic name
                'points' => collect([
                    (object)[ // Thêm epic vào points
                        'name' => $epic->name,
                        'y' => [
                            date('n/j/Y', strtotime($epic->plan_start_date)),
                            date('n/j/Y', strtotime($epic->plan_end_date))
                        ]
                    ]
                ])->merge($epic->children->map(function ($task) {
                    return (object)[ // Các task con
                        'name' => $task->name,
                        'y' => [
                            date('n/j/Y', strtotime($task->plan_start_date)),
                            date('n/j/Y', strtotime($task->plan_end_date))
                        ]
                    ];
                }))->values()->toArray(), // Chuyển về mảng
            ];
        })->values()->toArray(); // Chuyển về mảng

        // Lấy min/max date của tất cả task trong dự án
        $minDate = $tasks->min('plan_start_date');
        $maxDate = $tasks->max('plan_end_date');

        return view('pm.chart', compact('taskTree', 'minDate', 'maxDate'));
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
