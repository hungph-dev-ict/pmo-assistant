<?php

return [
    'required' => ':attribute is required.',
    'string' => ':attribute must be a string.',
    'integer' => ':attribute must be an integer.',
    'exists' => ':attribute is invalid.',
    'date' => ':attribute must be a valid date.',
    'numeric' => ':attribute must be a number.',
    'unique' => ':attribute has already been taken.',

    'max' => [
        'numeric' => ':attribute must not be greater than :max.',
        'string' => ':attribute must not exceed :max characters.',
    ],
    'min' => [
        'numeric' => ':attribute must be at least :min.',
        'string' => ':attribute must have at least :min characters.',
    ],

    'attributes' => [
        'project_name' => 'Project Name',
        'project_description' => 'Project Description',
        'project_status' => 'Status',
        'project_client_company' => 'Client Company',
        'project_project_manager' => 'Project Manager',
        'project_start_date' => 'Start Date',
        'project_end_date' => 'End Date',
        'project_estimated_budget' => 'Estimated Budget',
        'project_estimated_project_duration' => 'Estimated Project Duration',
    ],
];

