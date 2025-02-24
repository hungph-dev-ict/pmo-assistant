<?php

namespace App\Http\Middleware;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ShareProjectManagerData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $userId = Auth::id();
            if ($userId) {
                if (Auth::user()->hasRole('pm')) {
                    $projects = Project::where('project_manager', $userId)
                        ->with(['tasks:id,project_id']) // Chỉ lấy id của tasks
                        ->get();
                } elseif (Auth::user()->hasRole('client')) {
                    $projects = Project::whereIn('id', function ($query) use ($userId) {
                        $query->select('projects.id')
                            ->from('projects')
                            ->join('users as pm', 'pm.id', '=', 'projects.project_manager')
                            ->join('users as head_user', 'head_user.tenant_id', '=', 'pm.tenant_id')
                            ->where('head_user.id', $userId)
                            ->where('head_user.head_account_flg', true);
                    })
                        ->with(['tasks:id,project_id']) // Chỉ lấy id của tasks
                        ->get();
                } elseif (Auth::user()->hasRole('staff')) {
                    $projects = Auth::user()->projects()->with(['tasks:id,project_id'])->get();
                } else {
                    $projects = collect();
                }

                // Chia sẻ dữ liệu này với tất cả view
                view()->share('projects', $projects);
            }
        }

        return $next($request);
    }
}
