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
        ];
    }

    public function attributes(): array
    {
        return [
            'project_name' => __('validation.attributes.project_name'),
            'project_description' => __('validation.attributes.project_description'),
            'project_status' => __('validation.attributes.project_status'),
            'project_client_company' => __('validation.attributes.project_client_company'),
            'project_project_manager' => __('validation.attributes.project_project_manager'),
            'project_start_date' => __('validation.attributes.project_start_date'),
            'project_end_date' => __('validation.attributes.project_end_date'),
            'project_estimated_budget' => __('validation.attributes.project_estimated_budget'),
            'project_estimated_project_duration' => __('validation.attributes.project_estimated_project_duration'),
        ];
    }
}
