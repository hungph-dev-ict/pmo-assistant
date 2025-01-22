<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\Constant;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::paginate(10);
        return view('projects.index', compact('projects'));
    }

    public function create()
    {        
        $users_pmmanager = User::role('pm')->get();
        $projects_status = Constant::where('group', 'project_status')->get();
        return view('projects.create', compact('users_pmmanager', 'projects_status'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validate dữ liệu
        $validatedData = $request->validate([
            'name' => 'required|string|max:255', 
            'description' => 'required|string', 
            'status' => 'required|integer', 
            'client_company' => 'required|string|max:100',
            'project_manager' => 'required|integer|exists:users,id', 
            'start_date' => 'required|date', 
            'end_date' => 'required|date', 
            'estimated_budget' => 'required|numeric|min:0',
            'estimated_project_duration' => 'required|integer|min:0', 
        ]);
        // Lưu dữ liệu vào cơ sở dữ liệu
        $project = Project::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'status' => $validatedData['status'],
            'client_company' => $validatedData['client_company'],
            'project_manager' => $validatedData['project_manager'],
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'estimated_budget' => $validatedData['estimated_budget'],
            'estimated_project_duration' => $validatedData['estimated_project_duration'],
        ]);
    
        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($projectId)
    {
        return view('projects.show');
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
    public function destroy(Project $project)
    {
        //
    }

}