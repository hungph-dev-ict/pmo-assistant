<?php

namespace App\Http\Controllers;

use App\Models\Worklog;
use App\Models\Task;
use App\Models\Project;
use App\Services\WorklogService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WorklogController extends Controller
{
    protected $worklogService;

    public function __construct(WorklogService $worklogService)
    {
        $this->worklogService = $worklogService;
    }

    public function get($project_id, $task_id): JsonResponse
    {
        try {
            $worklog = $this->worklogService->getWorklogByTaskId($task_id);
            return response()->json(['message' => 'Worklog get successfully!', 'worklog' => $worklog], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to get worklog'], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $worklog = $this->worklogService->createWorklog($request->all());
            return response()->json(['message' => 'Worklog saved successfully!', 'worklog' => $worklog], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to save worklog'], 500);
        }
    }

    public function viewMyWorklog()
    {
        return view('worklog.my-worklog');
    }

    public function getMyWorklogs()
    {
        $myWorklogs = $this->worklogService->getMyWorklogs();

        return response()->json($myWorklogs, 201);
    }

    public function getMyLeaveRequests()
    {
        $myLeaveRequests = $this->worklogService->getMyLeaveRequests();

        return response()->json($myLeaveRequests, 201);
    }

    public function getProjectWorklogs($project_id)
    {
        $projectWorklogs = $this->worklogService->getProjectWorklogs($project_id);

        return response()->json($projectWorklogs, 201);
    }

    public function getProjectLeaveRequests($project_id)
    {
        $projectLeaveRequests = $this->worklogService->getProjectLeaveRequests($project_id);

        return response()->json($projectLeaveRequests, 201);
    }

    public function getWorlogAuditInWeek($project_id)
    {
        $worklogLacks = $this->worklogService->getUsersWithoutWorklogs($project_id);

        return response()->json($worklogLacks, 201);
    }

    public function getTenantWorklogs()
    {
        $tenantWorklogs = $this->worklogService->getTenantWorklogs();

        return response()->json($tenantWorklogs, 201);
    }

    public function getTenantLeaveRequests()
    {
        $tenantLeaveRequests = $this->worklogService->getTenantLeaveRequests();

        return response()->json($tenantLeaveRequests, 201);
    }

    public function viewProjectWorklogs($project_id)
    {
        $project = Project::findOrFail($project_id);

        return view('worklog.project-worklog', compact('project'));
    }

    public function viewTenantWorklogs()
    {
        return view('worklog.tenant-worklog');
    }

    public function updateWorklog(Request $request, $worklog_id)
    {
        try {
            // Kiểm tra task có tồn tại không
            $worklog = Worklog::findOrFail($worklog_id);

            // Validate dữ liệu đầu vào
            $validatedData = $request->validate([
                'log_date'    => 'required|date',
                'log_time'    => 'required|numeric|min:0.1',
                'description' => 'nullable|string',
            ]);

            $task = Task::findOrFail($worklog['task_id']);
            $task->update([
                'actual_effort' => $task->actual_effort - $worklog['log_time'] + $validatedData['log_time']
            ]);

            // Cập nhật thông tin task
            $worklog->update($validatedData);

            return response()->json([
                'message' => 'Task updated successfully!',
                'worklog' => $worklog,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Worklog not found!',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update Worklog!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function softDeleteWorklog($worklog_id)
    {
        $worklog = Worklog::find($worklog_id);

        if (!$worklog) {
            return response()->json(['error' => 'Worklog not found'], 404);
        }

        $task = Task::findOrFail($worklog['task_id']);

        $task->update([
            'actual_effort' => $task->actual_effort - $worklog['log_time']
        ]);

        $worklog->delete();

        return response()->json([
            'success' => 'Worklog deleted successfully',
            'deleted_tasks' => $worklog
        ], 200);
    }
}
