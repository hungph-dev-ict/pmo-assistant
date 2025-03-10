<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Project;
use App\Models\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\TaskService;
use App\Services\ProjectService;
use App\Http\Requests\StoreTaskRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PmController extends Controller
{
    protected $taskService;
    protected $projectService;

    public function __construct(TaskService $taskService, ProjectService $projectService)
    {
        $this->taskService = $taskService;
        $this->projectService = $projectService;
    }

    public function listTasks(Request $request, $project_id)
    {
        $project = Project::findOrFail($project_id);
        // if ($request->ajax()) {
        //     $data = $this->taskService->getTasksByProject($project_id);

        //     return response()->json($data);
        // }

        // Project Audit
        $today = Carbon::today();
        $project_audit = $this->projectService->calculateProjectMetrics($project_id, $today);

        $listAssignee = Project::with('users')->find($project_id)->users;

        return view('pm.task', compact('project_id', 'listAssignee', 'project', 'project_audit'));
    }

    public function listTasksByFilter(Request $request, $project_id)
    {
        $project = Project::findOrFail($project_id);
        if ($request->ajax()) {
            $data = $this->taskService->getTasksByFilter($project_id, $request);

            return response()->json($data);
        }
        $listAssignee = Project::with('users')->find($project_id)->users;

        return view('pm.task', compact('project_id', 'listAssignee', 'project'));
    }

    public function storeTask(StoreTaskRequest $request, $project_id)
    {
        try {
            // Chuyển đổi type từ string thành integer
            $typeMapping = [
                'epic' => 0,
                'task' => 1,
            ];

            // Validate dữ liệu đầu vào
            $validatedData = $request->validated();

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
                'priority' => 'required|integer|min:0|max:9',
                'assignee' => 'nullable|integer|exists:users,id',
                'status' => 'required|integer',
                'plan_start_date' => 'nullable|date',
                'plan_end_date' => 'nullable|date|after_or_equal:plan_start_date',
                'actual_start_date' => 'nullable|date',
                'actual_end_date' => 'nullable|date|after_or_equal:actual_start_date',
                'plan_effort' => 'nullable|numeric|min:0',
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
                return redirect()->route('pm.member', ['project_id' => $project_id])->with('warning', __('messages.no_changes_were_made'));
            }

            DB::commit(); // 🔹 Xác nhận thay đổi nếu không có lỗi
            return redirect()->route('pm.member', ['project_id' => $project_id])->with('success', __('messages.project_members_updated_success'));
        } catch (\Exception $e) {
            DB::rollBack(); // 🔹 Nếu có lỗi, hủy bỏ tất cả thay đổi
            return redirect()->route('pm.member', ['project_id' => $project_id])->with('error',  __('messages.error_updating_members', ['message' => $e->getMessage()]));
        }
    }

    public function viewChart($projectId)
    {
        $project = Project::find($projectId);
        $tasks = Task::where('project_id', $projectId)->get();

        $taskTree = $tasks->whereNull('parent_id')->map(function ($epic) {
            $startDate = $epic->plan_start_date ? date('n/j/Y', strtotime($epic->plan_start_date)) : null;
            $endDate = $epic->plan_end_date ? date('n/j/Y', strtotime($epic->plan_end_date)) : null;
            $yValues = ($startDate && $endDate) ? [$startDate, $endDate] : null; // Nếu cả hai đều null, set null

            return [
                'name' => Str::limit($epic->name, 27, '...'),
                'points' => collect([
                    (object)[
                        'name' => Str::limit($epic->name, 17, '...'),
                        'y' => $yValues
                    ]
                ])->merge($epic->children->map(function ($task) {
                    $startDate = $task->plan_start_date ? date('n/j/Y', strtotime($task->plan_start_date)) : null;
                    $endDate = $task->plan_end_date ? date('n/j/Y', strtotime($task->plan_end_date)) : null;
                    $yValues = ($startDate && $endDate) ? [$startDate, $endDate] : null; // Nếu cả hai đều null, set null

                    return (object)[
                        'name' => Str::limit($task->name, 17, '...'),
                        'y' => $yValues
                    ];
                }))->values()->toArray(),
            ];
        })->values()->toArray();

        $minDate = $tasks->whereNotNull('plan_start_date')->min('plan_start_date');
        $maxDate = $tasks->whereNotNull('plan_end_date')->max('plan_end_date');

        return view('pm.chart', compact('taskTree', 'minDate', 'maxDate', 'project'));
    }

    public function viewComponents($project_id)
    {
        $project = Project::find($project_id);
        $components = Component::withTrashed()->where('project_id', $project_id)->get();
        return view('pm.components', compact('components', 'project_id'));
    }

    public function createComponents(Request $request, $project_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'memo' => 'nullable|string',
        ]);

        Component::create([
            'project_id' => $project_id,
            'name' => $request->name,
            'memo' => $request->memo,
        ]);
        return redirect()->route('pm.components', ['project_id' => $project_id]);
    }

    public function destroy(Request $request, $project_id, $id)
    {

        $component = Component::findOrFail($id);
        $component->delete();
        return redirect()->route('pm.components', ['project_id' => $project_id]);
    }

    public function restore(Request $request, $project_id, $id)
    {
        $component = Component::withTrashed()->findOrFail($id);
        $component->restore();

        return redirect()->route('pm.components', ['project_id' => $project_id]);
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

    public function softDeleteTask($project_id, $task_id)
    {
        $task = Task::find($task_id);

        if (!$task) {
            return response()->json(['error' => 'Task not found'], 404);
        }

        if ($task->task_type == 0) { // Nếu là Epic
            // Lấy tất cả task con của Epic này
            $subTasks = Task::where('parent_id', $task_id)->get();
            foreach ($subTasks as $subTask) {
                $subTask->delete(); // Xóa mềm từng task con
            }
        }

        $task->delete(); // Xóa mềm task chính (Epic hoặc Task thường)

        return response()->json([
            'success' => 'Task deleted successfully',
            'deleted_tasks' => $task->task_type == 0 ? $subTasks->pluck('id') : [$task_id]
        ], 200);
    }
}
