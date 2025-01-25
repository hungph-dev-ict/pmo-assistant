<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTenantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Kiểm tra nếu user đã login và có vai trò admin
        return $this->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tenant_name' => 'required|string|max:60',
            'tenant_description' => 'required|string|max:500',
            'tenant_logo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:20480',
            'tenant_plan' => 'required|integer',
            'ha_email' => 'required|unique:users,email|email',
            'ha_account' => 'required|string|max:10',
            'ha_full_name' => 'required|string|max:60',
            'ha_avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:20480',
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
            'tenant_name' => 'Tenant Name',
            'tenant_description' => 'Tenant Description',
            'tenant_plan' => 'Plan',
            'tenant_logo' => 'Tenant Logo',
            'ha_email' => 'Head Account Email',
            'ha_account' => 'Head Account',
            'ha_full_name' => 'Head Account Full Name',
            'ha_avatar' => 'Head Account Avatar',
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
