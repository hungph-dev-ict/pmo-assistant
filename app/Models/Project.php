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
}
