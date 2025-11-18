<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Storage Disk
    |--------------------------------------------------------------------------
    |
    | The filesystem disk where uploaded files will be saved.
    |
    */
    'disk' => env('UPLOADMULTIPLEUI_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Upload Path
    |--------------------------------------------------------------------------
    |
    | Directory inside the selected disk where files will be stored.
    |
    */
    'path' => env('UPLOADMULTIPLEUI_PATH', 'uploads'),

    /*
    |--------------------------------------------------------------------------
    | Limits
    |--------------------------------------------------------------------------
    |
    | Maximum number of files and maximum file size (in kilobytes) allowed
    | for each upload request.
    |
    */
    'max_files' => env('UPLOADMULTIPLEUI_MAX_FILES', 10),
    'max_size' => env('UPLOADMULTIPLEUI_MAX_SIZE', 5120), // kilobytes (5 MB)

    /*
    |--------------------------------------------------------------------------
    | Routing
    |--------------------------------------------------------------------------
    |
    | Configure the middleware stack and URL prefix used by the package routes.
    |
    */
    'middleware' => ['web'],
    'route_prefix' => 'upload-multiple-ui',
];
