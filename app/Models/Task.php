<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'estimate_effort',
        'actual_effort',
        'progress',
        'priority',
        'status',
        'created_by',
        'type',
    ];


    // Relationship to children tasks
    public function children()
    {
        return $this->hasMany(Task::class, 'parent_id');
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
    public static function createTask($taskData)
    {
        // dd($taskData);

        $newTask = self::create([
            'description' => $taskData['description'],
            'name' =>  $taskData['name'],
            'estimate_effort' => $taskData['estimate_effort'],
            'actual_effort' => $taskData['actual_effort'],
            'assignee' => $taskData['assignee'],
            'progress' => $taskData['progress'] ?? 0,
            'plan_start_date' => $taskData['plan_start_date'],
            'plan_end_date' => $taskData['plan_end_date'],
            'actual_start_date' => $taskData['actual_start_date'],
            'actual_end_date' => $taskData['actual_end_date'],
            'priority' => '2',
            'status' => '0',
            'type' => $taskData['task_type'],
        ]);


    }

}
