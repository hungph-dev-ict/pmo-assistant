<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:epic,task',
            'parent_id' => 'nullable|exists:tasks,id',
            'description' => 'nullable|string',
            'memo' => 'nullable|string|max:512',
            'assignee' => 'nullable|integer|exists:users,id',
            'priority' => 'required|integer|min:0|max:4',
            'estimate_effort' => 'nullable|numeric|min:0',
            'actual_effort' => 'nullable|numeric|min:0',
            'plan_start_date' => 'nullable|date',
            'plan_end_date' => 'nullable|date|after_or_equal:plan_start_date',
            'actual_start_date' => 'nullable|date',
            'actual_end_date' => 'nullable|date|after_or_equal:actual_start_date',
            'status' => 'integer',
            'progress' => 'integer|min:0|max:100',
            'created_by' => 'required|exists:users,id',
        ];
    }
    public function attributes(): array
    {
        return [
            'name' => 'Epic/Task Title',
            'type' => 'Task Type',
            'parent_id' => 'Epic',
            'description' => 'Description',
            'memo' => 'Memo',
            'assignee' => 'Assignee',
            'priority' => 'Priority',
            'estimate_effort' => 'Plan Effort',
            'actual_effort' => 'Actual Effort',
            'plan_start_date' => 'Plan Start Date',
            'plan_end_date' => 'Plan End Date',
            'actual_start_date' => 'Actual Start Date',
            'actual_end_date' => 'Actual End Date',
            'status' => 'Status',
            'progress' => 'Progress',
            'created_by' => 'Created By',
        ];
    }
}
