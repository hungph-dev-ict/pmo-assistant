<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\ProjectService;
use Illuminate\Http\Request;

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
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(Project $project)
    {
        return view('projects.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
    }

        /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Xóa project bằng projectService
        $result = $this->projectService->deleteprojectById($id);
        
        if ($result) {
            return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
        }

        return redirect()->route('projects.index')->with('error', 'Failed to delete project.');
    }

    /**
     * Restore the specified project from soft deletes.
     */
    public function restore(string $id)
    {
        // Khôi phục project bằng projectService
        $result = $this->projectService->restoreProjectById($id);

        if ($result) {
            return redirect()->route('projects.index')->with('success', 'Project restored successfully.');
        }

        return redirect()->route('projects.index')->with('error', 'Failed to restore project.');
    }
}
