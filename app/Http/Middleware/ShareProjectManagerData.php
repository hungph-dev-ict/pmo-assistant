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
                //Lấy tenant_id của user
                $tenant_id = Auth::user()->tenant_id;
                // Kiểm tra role của user
                if (Auth::user()->hasRole('pm')) {
                    // Nếu người dùng là PM, lấy các dự án mà họ là project manager
                    $projects = Project::where('project_manager', $userId)->get();
                } elseif (Auth::user()->hasRole('client')) {
                    $projects = Project::whereIn('id', function ($query) use ($userId) {
                        $query->select('projects.id')
                            ->from('projects')
                            ->join('users as pm', 'pm.id', '=', 'projects.project_manager') // Kết nối với bảng users để lấy thông tin project manager (PM)
                            ->join('users as head_user', 'head_user.tenant_id', '=', 'pm.tenant_id') // Kết nối bảng users để xác định head user
                            ->where('head_user.id', $userId) // Lọc theo userId là head user
                            ->where('head_user.head_account_flg', true); // Chỉ lấy head user
                    })->get();
                } elseif (Auth::user()->hasRole('staff')) {
                    $projects = Auth::user()->projects;
                } else {
                    // Nếu không phải PM hay Client, không lấy dự án nào
                    $projects = collect();
                }

                // Chia sẻ dữ liệu này với tất cả view
                view()->share('projects', $projects);
                view()->share('tenant_id', $tenant_id);
            }
        }

        return $next($request);
    }
}
