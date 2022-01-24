<?php

return [

    'company_info' => [
        'name' => 'WMP',
        'email' => 'wmp@wmp.local',
        'webpage' => 'www.wmp.local',
        'phone' => '+370'
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
            'App\Category', 'App\Product', 'App\File'
        ],
        'tables' => [
            'product_files', 'category_files', 'related_products'
        ],
    ],

];