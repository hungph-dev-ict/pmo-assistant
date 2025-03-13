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
use Carbon\Carbon;
use App\Enums\ProjectStatus;

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
        $user = Auth::user();

        if ($user) {
            $tenantId = $user->tenant_id; // Lấy tenant_id của user

            // Kiểm tra role của user
            if ($user->hasRole('client')) {
                $projects = Project::withTrashed()
                    ->whereIn('id', function ($query) use ($tenantId) {
                        $query->select('projects.id')
                            ->from('projects')
                            ->join('users as pm', 'pm.id', '=', 'projects.project_manager') // Lấy thông tin PM
                            ->where('pm.tenant_id', $tenantId); // Lọc theo tenant của PM
                    })
                    ->paginate(10);
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

    public function getAllMembers($project_id)
    {
        try {
            $members = Project::findOrFail($project_id)->users()->pluck('users.id', 'users.account');

            return response()->json([
                'message' => 'Get list members of project successfully!',
                'members' => $members,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Member not found!',
                'error' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch members!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getProjectMetrics($projectId)
    {
        // Lấy ngày bắt đầu của project
        $project = Project::findOrFail($projectId);
        $startDate = Carbon::parse($project->start_date);
        // Nếu dự án đã đóng (closed), lấy end_date, ngược lại lấy hôm nay
        $endDate = $project->status === ProjectStatus::SUCCESS ? Carbon::parse($project->end_date) : Carbon::today();

        // Xác định khoảng thời gian
        $daysDiff = $startDate->diffInDays($endDate);

        if ($daysDiff <= 30) {
            $interval = 'day'; // Hiển thị theo ngày
        } elseif ($daysDiff <= 90) {
            $interval = 'week'; // Hiển thị theo tuần
        } else {
            $interval = 'month'; // Hiển thị theo tháng
        }

        // Tạo danh sách mốc thời gian phù hợp (labels)
        $labels = [];
        $datePointer = clone $startDate;

        while ($datePointer <= $endDate) {
            $labels[] = $datePointer->format('Y-m-d');

            if ($interval === 'day') {
                $datePointer->addDay();
            } elseif ($interval === 'week') {
                $datePointer->addWeek();
            } else {
                $datePointer->addMonth();
            }
        }

        // Lấy dữ liệu CPI, SPI theo từng mốc thời gian
        $cpiSeries = [];
        $spiSeries = [];

        foreach ($labels as $date) {
            $metrics = $this->projectService->calculateProjectMetrics($projectId, $date);
            $cpiSeries[] = $metrics['cpi'] ?? 0; // Nếu không có thì mặc định là 0
            $spiSeries[] = $metrics['spi'] ?? 0;
        }

        return response()->json([
            'labels' => $labels,
            'cpi_series' => $cpiSeries,
            'spi_series' => $spiSeries,
        ]);
    }
}
