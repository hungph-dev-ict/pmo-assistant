<?php

namespace App\Services;

use App\Models\User;
use App\Models\Task;
use App\Models\Worklog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class WorklogService
{
    public function getWorklogByTaskId($taskId)
    {
        return Worklog::where('task_id', $taskId)
            ->with(['user' => function ($query) {
                $query->select('id', 'name', 'account'); // ✅ Chỉ lấy các cột cần thiết
            }])
            ->orderBy('created_at', 'desc')
            ->get();
    }


    public function createWorklog($data)
    {
        // Validate dữ liệu
        $validator = Validator::make($data, [
            'task_id'     => 'required|exists:tasks,id',
            'log_date'    => 'required|date',
            'log_time'    => 'required|numeric|min:0.1',
            'description' => 'nullable|string',
        ]);

        $task = Task::findOrFail($data['task_id']);

        $task->update([
            'actual_effort' => $task->actual_effort + $data['log_time']
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        // Tạo worklog mới
        return Worklog::create([
            'task_id'     => $data['task_id'],
            'log_user'    => Auth::id(), // Lấy user hiện tại
            'log_date'    => $data['log_date'],
            'log_time'    => $data['log_time'],
            'description' => $data['description'] ?? null,
        ]);
    }

    public function getMyWorklogs()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $worklogs = Worklog::where('log_user', $user->id)->with('task.project')->orderBy('log_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $worklogs
        ]);
    }

    public function getProjectWorklogs($project_id)
    {
        // Lấy danh sách task_id thuộc về các project trong projectIds
        $taskIds = Task::where('project_id', $project_id)->pluck('id');

        // Lấy worklogs có task_id nằm trong danh sách taskIds
        $worklogs = Worklog::whereIn('task_id', $taskIds)
            ->with('user:id,account,name', 'task.project:id,name')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $worklogs
        ]);
    }

    public function getUsersWithoutWorklogs($project_id)
    {
        // Lấy danh sách user thuộc project
        $usersInProject = User::whereHas('projects', function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        })->pluck('id');

        // Lấy danh sách ngày hợp lệ từ thứ Hai đến hôm qua, bỏ thứ 7 và CN
        $startOfWeek = Carbon::now()->startOfWeek(); // Thứ Hai đầu tuần
        $yesterday = Carbon::yesterday(); // Hôm qua

        $validDays = [];
        $currentDate = $startOfWeek->copy();

        while ($currentDate->lte($yesterday)) {
            if (!in_array($currentDate->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY])) {
                $validDays[] = $currentDate->format('Y-m-d');
            }
            $currentDate->addDay();
        }

        // Lấy danh sách user đã log work vào từng ngày hợp lệ
        $worklogs = Worklog::whereHas('task', function ($query) use ($project_id) {
            $query->where('project_id', $project_id);
        })
            ->whereIn(DB::raw('DATE(created_at)'), $validDays)
            ->get(['log_user', 'created_at']);

        // Tạo một mảng chứa user và ngày họ đã log work
        $userWorklogDays = [];
        foreach ($worklogs as $worklog) {
            $date = Carbon::parse($worklog->created_at)->format('Y-m-d');
            $userWorklogDays[$worklog->log_user][] = $date;
        }

        // Tạo danh sách user và những ngày họ chưa log work
        $usersWithoutWorklogs = [];
        foreach ($usersInProject as $userId) {
            $missingDays = array_diff($validDays, $userWorklogDays[$userId] ?? []);
            if (!empty($missingDays)) {
                $usersWithoutWorklogs[] = [
                    'user_id' => $userId,
                    'user_name' => User::find($userId)->name,
                    'missing_days' => array_values($missingDays) // Convert array keys to sequential
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $usersWithoutWorklogs
        ]);
    }

    public function getTenantWorklogs()
    {
        $user = Auth::user();

        if (!$user || !$user->hasRole('client')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $tenant_user_ids = User::where('tenant_id', $user->tenant_id)->pluck('id')->all();

        $worklogs = Worklog::whereIn('log_user', $tenant_user_ids)->with('user', 'task.project', 'task.assigneeUser')->orderBy('log_date', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $worklogs
        ]);
    }

    public function getActualEffortByProject($projectId)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $totalLogTime = Worklog::whereHas('task', function ($query) use ($projectId) {
            $query->where('project_id', $projectId);
        })->sum('log_time');

        return $totalLogTime;
    }
}
