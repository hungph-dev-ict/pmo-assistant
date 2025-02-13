<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
                'estimate_effort' => 'numeric|min:0',
                'actual_effort' => 'nullable|numeric|min:0',
                'plan_start_date' => 'nullable|date',
                'plan_end_date' => 'nullable|date|after_or_equal:plan_start_date',
                'actual_start_date' => 'nullable|date',
                'actual_end_date' => 'nullable|date|after_or_equal:actual_start_date',
                'status' => 'integer',
                'progress' => 'integer|min:0|max:100',
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

    public function listEpics($project_id)
    {
        $data = Task::where('project_id', $project_id)->where('type', '0')->get();

        return response()->json($data);
    }

    public function listMembers($project_id)
    {
        $project = Project::findOrFail($project_id);

        $membersInProject = $project->users()->with('jobPosition')->get(); // Danh sách user trong dự án
        $membersNotInProject = User::whereDoesntHave('projects', function ($query) use ($project_id) {
            $query->where('projects.id', $project_id);
        })->where('tenant_id', auth()->user()->tenant_id)->with('jobPosition')->get(); // Danh sách user chưa tham gia

        return view('pm.member', compact('project', 'membersInProject', 'membersNotInProject'));
    }

    public function updateMembers(Request $request, $project_id)
    {
        try {
            DB::beginTransaction(); // 🔹 Bắt đầu transaction

            // Decode JSON từ input ẩn
            $selected_user_list = json_decode($request->input('selected_user_list', '[]'), true);

            $project = Project::findOrFail($project_id);
            $syncResult = $project->users()->sync($selected_user_list);

            if (empty($syncResult['attached']) && empty($syncResult['detached']) && empty($syncResult['updated'])) {
                DB::rollBack(); // 🔹 Không có thay đổi, rollback để tránh cập nhật không cần thiết
                return redirect()->route('pm.member', ['project_id' => $project_id])->with('warning', 'No changes were made!');
            }

            DB::commit(); // 🔹 Xác nhận thay đổi nếu không có lỗi
            return redirect()->route('pm.member', ['project_id' => $project_id])->with('success', 'Project members updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack(); // 🔹 Nếu có lỗi, hủy bỏ tất cả thay đổi
            return redirect()->route('pm.member', ['project_id' => $project_id])->with('error', 'Error updating members: ' . $e->getMessage());
        }
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

        return view('pm.chart', compact('taskTree', 'minDate', 'maxDate', 'project'));
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
