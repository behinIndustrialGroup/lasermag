<?php

return [
    'cdr_connection' => env('AMI_CDR_CONNECTION'),
    'cdr_table' => env('AMI_CDR_TABLE', 'cdr'),
    'columns' => [
        'date' => env('AMI_CDR_DATE_COLUMN', 'calldate'),
        'source' => env('AMI_CDR_SOURCE_COLUMN', 'src'),
        'destination' => env('AMI_CDR_DESTINATION_COLUMN', 'dst'),
        'duration' => env('AMI_CDR_DURATION_COLUMN', 'billsec'),
        'duration_fallback' => env('AMI_CDR_DURATION_FALLBACK_COLUMN', 'duration'),
        'status' => env('AMI_CDR_STATUS_COLUMN', 'disposition'),
        'recording' => env('AMI_CDR_RECORDING_COLUMN', 'recordingfile'),
    ],
    'default_limit' => env('AMI_CDR_LIMIT', 50),
    'recordings' => [
        'disk' => env('AMI_RECORDINGS_DISK'),
        'prefix' => env('AMI_RECORDINGS_PREFIX', ''),
        'base_path' => env('AMI_RECORDINGS_BASE_PATH'),
        'base_url' => env('AMI_RECORDINGS_BASE_URL'),
        'download' => [
            'url' => env('AMI_RECORDINGS_DOWNLOAD_URL'),
            'username' => env('AMI_RECORDINGS_DOWNLOAD_USERNAME'),
            'password' => env('AMI_RECORDINGS_DOWNLOAD_PASSWORD'),
            'verify_ssl' => env('AMI_RECORDINGS_DOWNLOAD_VERIFY_SSL', false),
            'timeout' => env('AMI_RECORDINGS_DOWNLOAD_TIMEOUT'),
        ],
        'issabel' => [
            'base_url' => env('AMI_ISSABEL_BASE_URL'),
            'username' => env('AMI_ISSABEL_USERNAME'),
            'password' => env('AMI_ISSABEL_PASSWORD'),
            'login_path' => env('AMI_ISSABEL_LOGIN_PATH', '/index.php?module=auth&action=login'),
            'username_field' => env('AMI_ISSABEL_USERNAME_FIELD', 'input_user'),
            'password_field' => env('AMI_ISSABEL_PASSWORD_FIELD', 'input_pass'),
            'extra_fields' => [],
            'verify_ssl' => env('AMI_ISSABEL_VERIFY_SSL', true),
            'timeout' => env('AMI_ISSABEL_TIMEOUT', 15),
        ],
    ],
];
