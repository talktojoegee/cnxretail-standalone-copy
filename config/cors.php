<?php

$frontend = array_values(array_filter(array_map('trim', explode(',', (string) env('FRONTEND_URL', '*')))));

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Configure allowed origins for the Next.js frontends that call /api/*.
    | Set FRONTEND_URL to a comma-separated list (e.g. http://localhost:3000).
    | Bearer JWT auth does not require credentialed cookies.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => $frontend === [] ? ['*'] : $frontend,

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => ['X-Cart-Token'],

    'max_age' => 0,

    'supports_credentials' => false,

];
