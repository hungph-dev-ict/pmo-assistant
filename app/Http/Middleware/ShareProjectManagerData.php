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
        $pm_projects = collect();
        $client_projects = collect();
        $staff_projects = collect();

        if (auth()->check()) {
            $userId = Auth::id();
            if ($userId) {
                if (Auth::user()->hasRole('pm')) {
                    $pm_projects = Project::whereHas('users', function ($query) use ($userId) {
                        $query->where('user_id', $userId);
                    })
                    ->with(['tasks:id,project_id'])
                    ->get();
                }

                if (Auth::user()->hasRole('client')) {
                    $client_projects = Project::whereIn('id', function ($query) use ($userId) {
                        $query->select('projects.id')
                            ->from('projects')
                            ->join('users as pm', 'pm.id', '=', 'projects.project_manager')
                            ->join('users as head_user', 'head_user.tenant_id', '=', 'pm.tenant_id')
                            ->where('head_user.id', $userId);
                    })
                        ->with(['tasks:id,project_id'])
                        ->get();
                }

                if (Auth::user()->hasRole('staff')) {
                    $staff_projects = Auth::user()->projects()->with(['tasks:id,project_id'])->get();
                }

                // Chia sẻ từng loại dự án với tất cả view
                view()->share([
                    'pm_projects' => $pm_projects,
                    'client_projects' => $client_projects,
                    'staff_projects' => $staff_projects,
                ]);
            }
        }

        return $next($request);
    }
}
