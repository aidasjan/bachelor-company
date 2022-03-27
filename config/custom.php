<?php

return [

    'gateway_url' => env('GATEWAY_URL'),

    'company_info' => [
        'id' => env('COMPANY_ID'),
        'name' => 'WMP',
        'email' => 'wmp@wmp.local',
        'webpage' => 'www.wmp.local',
        'phone' => '+370',
        'logo_url' => env('LOGO_URL')
    ],

    'files' => [
        'product_file' => [
            'allowed_file_types' => 'jpeg,jpg,png,pdf,xlsx,docx',
            'max_file_size' => '10000',
        ],
        'category_file' => [
            'allowed_file_types' => 'jpeg,jpg,png',
            'max_file_size' => '10000',
        ],
        'import_file' => [
            'allowed_file_types' => 'xlsx',
            'max_file_size' => '10000',
        ]
    ],

    'search' => [
        'results_limit' => 30,
    ],

    'backup' => [
        'token' => env('BACKUP_ACCESS_TOKEN'),
        'interval_days' => 7,
        'backups_to_keep' => 12,
        'models' => [
            'App\Models\Category', 'App\Models\Product', 'App\Models\File'
        ],
        'tables' => [
            'product_files', 'category_files', 'related_products'
        ],
    ],

];