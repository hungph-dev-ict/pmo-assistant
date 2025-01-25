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
            // Lấy id người dùng hiện tại
            $userId = Auth::id();
            if ($userId) {
                // Kiểm tra role của user
                if (Auth::user()->hasRole('pm')) {
                    // Nếu người dùng là PM, lấy các dự án mà họ là project manager
                    $projects = Project::where('project_manager', $userId)->get();
                } elseif (Auth::user()->hasRole('client')) {
                    // Lấy các dự án mà người quản lý dự án (project_manager) thuộc tenant mà userId đang quản lý (head_user_id)
                    $projects = Project::whereIn('id', function ($query) use ($userId) {
                        $query->select('projects.id')
                            ->from('projects')
                            ->join('users', 'users.id', '=', 'projects.project_manager') // Kết nối bảng users để lấy thông tin PM
                            ->join('tenants', 'tenants.id', '=', 'users.tenant_id') // Kết nối bảng tenants để lấy tenant của PM
                            ->where('tenants.head_user_id', $userId); // Lọc theo tenant mà người dùng đang quản lý
                    })->get();
                } else {
                    // Nếu không phải PM hay Client, không lấy dự án nào
                    $projects = collect();
                }

                // Chia sẻ dữ liệu này với tất cả view
                view()->share('projects', $projects);
            }
        }

        return $next($request);
    }
}
