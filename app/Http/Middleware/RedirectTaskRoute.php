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

        if ($user->hasRole('client') || $user->hasRole('pm')) {
            return redirect()->route('pm.task.show', ['project_id' => $projectId, 'task_id' => $taskId]);
        } else if ($user->hasRole('staff')) {
            return redirect()->route('staff.task.show', ['project_id' => $projectId, 'task_id' => $taskId]);
        }

        return $next($request);
    }
}
