<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    */

    // Pastikan semua jalur rute Anda masuk di sini
    'paths' => ['api/*', 'v1/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    // Tulis port Vite Anda secara spesifik di sini (jangan pakai '*')
    'allowed_origins' => [
        'http://localhost:5173', 
        'http://127.0.0.1:5173',
       ' https://presensi-smk-frontend.vercel.app'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // Setel ke true agar frontend bisa mengirim token/cookie/session dengan aman
    'supports_credentials' => true,

];