<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Worklog extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'task_id',
        'log_user',
        'log_date',
        'log_time',
        'description',
    ];

    public function task() {
        return $this->belongsTo(Task::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'log_user');
    }
}

