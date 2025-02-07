<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Kiểm tra nếu user đã login và có vai trò client
        return $this->user()->hasRole('client');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'project_name' => 'required|string|max:255', 
            'project_description' => 'required|string', 
            'project_status' => 'required|integer', 
            'project_client_company' => 'required|string|max:100',
            'project_project_manager' => 'required|integer|exists:users,id', 
            'project_start_date' => 'required|date', 
            'project_end_date' => 'required|date', 
            'project_estimated_budget' => 'required|numeric|min:0',
            'project_estimated_project_duration' => 'required|integer|min:0', 
        ];
    }

    public function attributes(): array
    {
        return [
            'project_name' => 'Project Name', 
            'project_description' => 'Project Description', 
            'project_status' => 'Status', 
            'project_client_company' => 'Client Company',
            'project_project_manager' => 'Project Manager', 
            'project_start_date' => 'Start Date', 
            'project_end_date' => 'End Date', 
            'project_estimated_budget' => 'Estimated budget',
            'project_estimated_project_duration' => 'Estimated project duration', 
        ];
    }
}
