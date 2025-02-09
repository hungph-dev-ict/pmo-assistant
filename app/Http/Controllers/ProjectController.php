<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Constant;
use Illuminate\Http\Request;
use App\Services\ProjectService;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectController extends Controller
{
    protected $projectService;

    // Tiêm ProjectService vào controller thông qua constructor
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::withTrashed()->paginate(10);
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
                ->with('success', 'Project created successfully.');
        }

        return 500;
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('projects.show',compact('project'));
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
                ->with('success', 'Project edited successfully.');
        }

        return 500;
    }

        /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }

}