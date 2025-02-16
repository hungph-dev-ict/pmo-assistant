<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'status',
        'client_company',
        'project_manager',
        'start_date',
        'end_date',
        'estimated_budget',
        'estimated_project_duration',
    ];
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user'); // Mối quan hệ nhiều-nhiều giữa Project và User
    }

    public function projectManager()
    {
        return $this->belongsTo(User::class, 'project_manager', 'id');
    }

    public function projectStatus()
    {
        return $this->belongsTo(Constant::class, 'status', 'key')
            ->where('group', 'project_status');
    }
    public static function createProject($projectData)
    {
        $newProject = self::create([
            'name' => $projectData['project_name'],
            'description' => $projectData['project_description'],
            'status' => $projectData['project_status'],
            'client_company' => $projectData['project_client_company'],
            'project_manager' => $projectData['project_project_manager'],
            'start_date' => $projectData['project_start_date'],
            'end_date' => $projectData['project_end_date'],
            'estimated_budget' => $projectData['project_estimated_budget'],
        ]);

        return $newProject;
    }

    public static function updateProject($idProject, $projectData)
    {
        // Lấy thông tin tenant hiện tại
        $project = self::find($idProject);
        if (!$project) {
            throw new \Exception("Project không tồn tại.");
        }

        // Cập nhật thông tin tenant
        $project->update([
            'name' => $projectData['project_name'],
            'description' => $projectData['project_description'],
            'status' => $projectData['project_status'],
            'client_company' => $projectData['project_client_company'],
            'project_manager' => $projectData['project_project_manager'],
            'start_date' => $projectData['project_start_date'],
            'end_date' => $projectData['project_end_date'],
            'estimated_budget' => $projectData['project_estimated_budget'],
            'estimated_project_duration' => $projectData['project_estimated_project_duration'],
        ]);

        return $project;
    }
}
