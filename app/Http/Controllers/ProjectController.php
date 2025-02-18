<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Constant;
use App\Services\ProjectService;
use App\Services\WorklogService;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    protected $projectService;
    protected $worklogService;

    // Tiêm ProjectService vào controller thông qua constructor
    public function __construct(ProjectService $projectService, WorklogService $worklogService)
    {
        $this->projectService = $projectService;
        $this->worklogService = $worklogService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = collect(); // Mặc định là collection rỗng
        $userId = Auth::id();

        if ($userId) {
            // Kiểm tra role của user
            if (Auth::user()->hasRole('pm')) {
                // Nếu người dùng là PM, lấy các dự án mà họ là project manager
                $projects = Project::withTrashed()
                    ->where('project_manager', $userId)
                    ->paginate(10);
            } elseif (Auth::user()->hasRole('client')) {
                $projects = Project::withTrashed()
                    ->whereIn('id', function ($query) use ($userId) {
                        $query->select('projects.id')
                            ->from('projects')
                            ->join('users as pm', 'pm.id', '=', 'projects.project_manager') // Kết nối với bảng users để lấy thông tin project manager (PM)
                            ->join('users as head_user', 'head_user.tenant_id', '=', 'pm.tenant_id') // Kết nối bảng users để xác định head user
                            ->where('head_user.id', $userId) // Lọc theo userId là head user
                            ->where('head_user.head_account_flg', true); // Chỉ lấy head user
                    })
                    ->paginate(10);
            } elseif (Auth::user()->hasRole('staff')) {
                $projects = Auth::user()->projects()->withTrashed()->paginate(10);
            }

            // Gán tổng actual effort cho từng project
            foreach ($projects as $project) {
                $project->total_actual_effort = $this->worklogService->getActualEffortByProject($project->id);
            }
        }

        return view('projects.index', compact('projects'));
    }


    public function create()
    {
        $listProjectManager  = User::role('pm')->where('tenant_id', auth()->user()->tenant_id)->get();
        $projectStatuses = Constant::where('group', 'project_status')->get();
        return view('projects.create', compact('listProjectManager', 'projectStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateProjectRequest $request)
    {
        // Dữ liệu đã được validate
        $newProjectInfo = $request->validated();

        $createNewProject = $this->projectService->createProject($newProjectInfo);

        if ($createNewProject) {
            return redirect()->route('projects.index')
                ->with('success', __('messages.project_created_success'));
        }

        return 500;
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $totalActualEffort = $this->worklogService->getActualEffortByProject($project->id);

        return view('projects.show', compact('project', 'totalActualEffort'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $project = Project::findOrFail($id);

        $listProjectManager = User::role('pm')->where('tenant_id', auth()->user()->tenant_id)->get();
        $projectStatuses = Constant::where('group', 'project_status')->get();

        return view('projects.edit', compact('project', 'listProjectManager', 'projectStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, string $idProject)
    {
        // Dữ liệu đã được validate
        $updateProjectInfo = $request->validated();

        $updateNewProject = $this->projectService->updateProject($idProject, $updateProjectInfo);
        if ($updateNewProject) {
            return redirect()->route('projects.index')
                ->with('success', __('messages.project_updated_success'));
        }

        return 500;
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        // Xóa Project bằng ProjectService
        $result = $this->projectService->deleteProjectById($id);

        if ($result) {
            return redirect()->route('projects.index')->with('success', __('messages.project_delete_success'));
        }

        return redirect()->route('projects.index')->with('error', __('messages.failed_to_delete_project'));
    }

    /**
     * Restore the specified Project from soft deletes.
     */
    public function restore(string $id)
    {
        // Khôi phục Project bằng ProjectService
        $result = $this->projectService->restoreProjectById($id);

        if ($result) {
            return redirect()->route('projects.index')->with('success', __('messages.project_restored_success'));
        }

        return redirect()->route('projects.index')->with('error', __('messages.failed_to_restore_project'));
    }
}
