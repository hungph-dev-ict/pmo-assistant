<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveRequest extends Model {
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'leave_user',
        'leave_type',
        'leave_date',
        'leave_start_time',
        'leave_end_time',
        'leave_reason',
        'leave_status'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'leave_user');
    }

    public function approvers() {
        return $this->belongsToMany(User::class, 'leave_request_approver', 'leave_request_id', 'leave_approver')->withTimestamps();
    }

    public function leaveRequestType()
    {
        return $this->belongsTo(Constant::class, 'leave_type', 'key')
            ->where('group', 'leave_request_type');
    }
    public function leaveRequestStatus()
    {
        return $this->belongsTo(Constant::class, 'leave_status', 'key')
            ->where('group', 'leave_request_status');
    }
}

