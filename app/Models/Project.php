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

    public function tasks()
    {
        return $this->hasMany(Task::class, 'project_id', 'id');
    }
    
    public static function createProject($projectData)
    {
        $newProject = self::create([
            'name' => $projectData['project_name'] ?? 'default name',
            'description' => $projectData['project_description'] ?? null,
            'status' => $projectData['project_status'] ?? '1',
            'client_company' => $projectData['project_client_company'] ?? null,
            'project_manager' => $projectData['project_project_manager'] ?? null,
            'start_date' => $projectData['project_start_date'] ?? null,
            'end_date' => $projectData['project_end_date'] ?? null,
            'estimated_budget' => $projectData['project_estimated_budget'] ?? null,
        ]);

        return $newProject;
    }

    public static function updateProject($idProject, $projectData)
    {
        // Lấy thông tin tenant hiện tại
        $project = self::find($idProject);
        if (!$project) {
            throw new \Exception(__('messages.project_not_found'));
        }

        // Cập nhật thông tin tenant
        $project->update([
            'name' => $projectData['project_name'] ?? 'default name',
            'description' => $projectData['project_description'] ?? null,
            'status' => $projectData['project_status'] ?? '1',
            'client_company' => $projectData['project_client_company'] ?? null,
            'project_manager' => $projectData['project_project_manager'],
            'start_date' => $projectData['project_start_date'] ?? null,
            'end_date' => $projectData['project_end_date'] ?? null,
            'estimated_budget' => $projectData['project_estimated_budget'] ?? null,
        ]);

        return $project;
    }
}
