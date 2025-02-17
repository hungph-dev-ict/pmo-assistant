<?php

return [
    'required' => ':attribute là bắt buộc.',
    'string' => ':attribute phải là chuỗi.',
    'integer' => ':attribute phải là số nguyên.',
    'exists' => ':attribute không hợp lệ.',
    'date' => ':attribute phải là ngày hợp lệ.',
    'numeric' => ':attribute phải là số.',
    'unique' => ':attribute đã tồn tại.',

    'max' => [
        'numeric' => ':attribute không được lớn hơn :max.',
        'string' => ':attribute không được vượt quá :max ký tự.',
    ],
    'min' => [
        'numeric' => ':attribute phải lớn hơn hoặc bằng :min.',
        'string' => ':attribute phải có ít nhất :min ký tự.',
    ],

    'attributes' => [
        //Project
        'project_name' => 'Tên dự án',
        'project_description' => 'Mô tả dự án',
        'project_status' => 'Trạng thái',
        'project_client_company' => 'Công ty khách hàng',
        'project_project_manager' => 'Quản lý dự án',
        'project_start_date' => 'Ngày bắt đầu',
        'project_end_date' => 'Ngày kết thúc',
        'project_estimated_budget' => 'Ngân sách ước tính',
        'project_estimated_project_duration' => 'Thời gian ước tính',

        //Tenant
        'tenant_name' => 'Tên Tenant',
        'tenant_description' => 'Mô tả Tenant',
        'tenant_plan' => 'Gói dịch vụ',
        'tenant_logo' => 'Logo Tenant',
        'ha_email' => 'Email',
        'ha_account' => 'Tài khoản',
        'ha_full_name' => 'Tên đầy đủ',
        'ha_avatar' => 'Ảnh đại diện',
        'ha_password' => 'Mật khẩu',
    ],
];