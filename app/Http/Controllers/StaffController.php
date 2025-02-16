<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Project;
use App\Services\TaskService;

class StaffController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function listTasks(Request $request, $project_id)
    {
        $project = Project::findOrFail($project_id);
        if ($request->ajax()) {
            $tasks = Task::where('project_id', $project_id)->with('assigneeUser')->get();
            $data = $this->taskService->getTasksByProject($project_id);

            return response()->json($data);
        }
        $listAssignee = Project::with('users')->find($project_id)->users;
        
        return view('staff.task', compact('project_id', 'listAssignee', 'project'));
    }

    public function updateTask(Request $request, $project_id, $task_id)
    {
        try {
            // Kiểm tra task có tồn tại không
            $task = Task::where('project_id', $project_id)->findOrFail($task_id);

            // Validate dữ liệu đầu vào
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'priority' => 'required|integer|min:0|max:4',
                'assignee' => 'nullable|integer|exists:users,id',
                'status' => 'required|integer',
                'plan_start_date' => 'nullable|date',
                'plan_end_date' => 'nullable|date|after_or_equal:plan_start_date',
                'actual_start_date' => 'nullable|date',
                'actual_end_date' => 'nullable|date|after_or_equal:actual_start_date',
                'estimate_effort' => 'numeric|min:0',
            ]);

            // Cập nhật thông tin task
            $task->update($validatedData);

            return response()->json([
                'message' => 'Task updated successfully!',
                'task' => $task,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Task not found!',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update task!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function listMembers()
    {
        return view('staff.member');
    }


    public function viewChart($projectId)
    {
        $project = Project::find($projectId);
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

        return view('staff.chart', compact('taskTree', 'minDate', 'maxDate', 'project'));
    }
}
