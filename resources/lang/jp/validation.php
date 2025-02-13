<?php

return [
    'required' => ':attribute は必須です。',
    'string' => ':attribute は文字列である必要があります。',
    'integer' => ':attribute は整数である必要があります。',
    'exists' => ':attribute は無効です。',
    'date' => ':attribute は有効な日付である必要があります。',
    'numeric' => ':attribute は数値である必要があります。',
    'unique' => ':attribute は既に存在しています。',

    'max' => [
        'numeric' => ':attribute は:max を超えてはいけません。',
        'string' => ':attribute は最大:max文字までです。',
    ],
    'min' => [
        'numeric' => ':attribute は最低:minである必要があります。',
        'string' => ':attribute は最低:min文字必要です。',
    ],

    'attributes' => [
        'project_name' => 'プロジェクト名',
        'project_description' => 'プロジェクトの説明',
        'project_status' => 'ステータス',
        'project_client_company' => 'クライアント会社',
        'project_project_manager' => 'プロジェクトマネージャー',
        'project_start_date' => '開始日',
        'project_end_date' => '終了日',
        'project_estimated_budget' => '予算見積もり',
        'project_estimated_project_duration' => '推定プロジェクト期間',
    ],
];


