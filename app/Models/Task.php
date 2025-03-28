<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Task extends Model
{
    use HasFactory, SoftDeletes;
    protected $attributes = [
        'progress' => 0,
    ];

    protected $fillable = [
        'name',
        'description',
        'project_id',
        'parent_id',
        'assignee',
        'plan_start_date',
        'plan_end_date',
        'actual_start_date',
        'actual_end_date',
        'plan_effort',
        'actual_effort',
        'progress',
        'priority',
        'status',
        'created_by',
        'type',
        'memo'
    ];


    // Relationship to children tasks
    public function children()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function worklogs()
    {
        return $this->hasMany(Worklog::class, 'task_id');
    }

    // Relationship to parent task
    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    // Relationship to assignee (user)
    public function assigneeUser()
    {
        return $this->belongsTo(User::class, 'assignee');
    }

    // Relationship with Project
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // Relationship with Creator
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function taskStatus()
    {
        return $this->hasOne(Constant::class, 'key', 'status')
            ->where('group', 'task_status');
    }

    public function taskPriority()
    {
        return $this->hasOne(Constant::class, 'key', 'priority')
            ->where('group', 'task_priority');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y/m/d H:i:s');
    }

    public function components()
    {
        return $this->belongsToMany(Component::class, 'task_component');
    }
}
