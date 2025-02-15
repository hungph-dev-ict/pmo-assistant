<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            'user_name' => 'required|string|max:255', 
            'user_account' => 'required|string|max:255',
            'user_email' => 'required|string|max:255|unique:users,email',
            'user_job_position' => 'required|string|max:255', 
            'user_password' => 'required|string|min:8|max:255', 
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'user_name' => 'Full Name', 
            'user_account' => 'Account',
            'user_email' => 'Email Address',
            'user_job_position' => 'Job Position', 
            'user_password' => 'Password',  
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            //
        ];
    }
}