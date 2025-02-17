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
            'ha_password' => 'required|min:8|max:30',
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
            'tenant_name' => __('validation.attributes.tenant_name'),
            'tenant_description' => __('validation.attributes.tenant_description'),
            'tenant_plan' => __('validation.attributes.tenant_plan'),
            'tenant_logo' => __('validation.attributes.tenant_logo'),
            'ha_email' => __('validation.attributes.ha_email'),
            'ha_account' => __('validation.attributes.ha_account'),
            'ha_full_name' => __('validation.attributes.ha_full_name'),
            'ha_avatar' => __('validation.attributes.ha_avatar'),
            'ha_password' => __('validation.attributes.ha_password'),
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
