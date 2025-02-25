<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectTaskRoute
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $taskId = $request->route('task_id');
        $projectId = $request->route('project_id');

        // Lấy URL hiện tại để kiểm tra nếu user đang ở nhầm route
        $currentPath = $request->path();

        if ($user->hasRole('pm') && str_contains($currentPath, 'staff/')) {
            return redirect()->route('pm.task.show', ['project_id' => $projectId, 'task_id' => $taskId]);
        }

        if ($user->hasRole('staff') && str_contains($currentPath, 'pm/')) {
            return redirect()->route('staff.task.show', ['project_id' => $projectId, 'task_id' => $taskId]);
        }

        return $next($request);
    }
}
