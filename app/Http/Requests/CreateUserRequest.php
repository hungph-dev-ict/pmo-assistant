<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'user_job_position' => [
                'required',
                'string',
                'max:255',
                Rule::notIn([$this->input('user_sub_role_1'), $this->input('user_sub_role_2')])
            ],
            'user_sub_role_1' => [
                'nullable',
                'string',
                'max:255',
                Rule::notIn([$this->input('user_job_position'), $this->input('user_sub_role_2')])
            ],
            'user_sub_role_2' => [
                'nullable',
                'string',
                'max:255',
                Rule::notIn([$this->input('user_job_position'), $this->input('user_sub_role_1')])
            ],
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
            'user_job_position' => 'Main Role',
            'user_sub_role_1' => 'Sub Role 1',
            'user_sub_role_2' => 'Sub Role 2',
            'user_password' => 'Password',
        ];
    }

    public function messages(): array
    {
        return [
            'user_job_position.not_in' => 'Sub Role 1 or Sub Role 2 must be different from the Main Role.',
            'user_sub_role_1.not_in' => 'Sub Role 1 must be different from the Main Role.',
            'user_sub_role_2.not_in' => 'Sub Role 2 must be different from the Main Role and Sub Role 1.',
        ];
    }
}
