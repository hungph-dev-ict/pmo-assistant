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
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'actual_end_date' => 'required|date',
            'actual_start_date' => 'required|date',
            'plan_start_date' => 'required|date',
            'plan_end_date' => 'required|date',
            'progress' => 'required|integer|min:0|max:100',
            'actual_effort' => 'required|integer|min:0',
            'estimate_effort' => 'required|integer|min:0',
            'parent_task' => 'required|string',
            'task' => 'required|string',
            'assignee' => 'nullable|integer|exists:users,id',
            'parent_id' => 'nullable|integer|exists:tasks,id',

        ];
    }
    public function attributes(): array
    {
        return [
            'actual_end_date' => 'Actual End Date',
            'actual_start_date' => 'Actual Start Date',
            'plan_start_date' => 'Plan Start Date',
            'plan_end_date' => 'Plan End Date',
            'progress' => 'Progress',
            'actual_effort' => 'Actual Effort (MD)',
            'estimate_effort' => 'Estimate Effort (MD)',
            'parent_task' => 'Parent Task',
            'task' => 'Task',
            'parent_id' => 'ID',
            'assignee' => 'Assignee',
        ];
    }
}
