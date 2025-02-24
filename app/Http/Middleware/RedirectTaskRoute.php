<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectTaskRoute
{
    public function handle(Request $request, Closure $next)
    {
        // Nếu chưa đăng nhập, chuyển hướng đến trang login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $taskId = $request->route('task_id'); // Lấy ID task từ URL
        $projectId = $request->route('project_id'); // Lấy ID project từ URL

        // Kiểm tra vai trò của user và điều hướng tương ứng
        if ($user->hasRole('client|pm')) {
            return redirect()->route('pm.task.show', ['project_id' => $projectId, 'task_id' => $taskId]);
        } elseif ($user->hasRole('staff')) {
            return redirect()->route('staff.task.show', ['project_id' => $projectId, 'task_id' => $taskId]);
        }

        return abort(403, 'Bạn không có quyền truy cập.');
    }
}
