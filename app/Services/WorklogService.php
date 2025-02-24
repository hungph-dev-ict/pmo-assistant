<?php

namespace App\Services;

use App\Models\User;
use App\Models\Task;
use App\Models\Worklog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class WorklogService {
    public function createWorklog($data) {
        // Validate dữ liệu
        $validator = Validator::make($data, [
            'task_id'     => 'required|exists:tasks,id',
            'log_date'    => 'required|date',
            'log_time'    => 'required|numeric|min:0.1',
            'description' => 'nullable|string',
        ]);

        $task = Task::findOrFail($data['task_id']);
        
        $task ->update([
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

    public function getMyWorklogs() {
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

    public function getTenantWorklogs() {
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

    public function getActualEffortByProject($projectId) {
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
