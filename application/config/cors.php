<?php

defined('BASEPATH') or exit('No direct script access allowed');

$config = [
    /*
     |--------------------------------------------------------------------------
     | Cross-Origin Resource Sharing (CORS) Configuration
     |--------------------------------------------------------------------------
     |
     | Here you may configure your settings for cross-origin resource sharing
     | or "CORS". This determines what cross-origin operations may execute
     | in web browsers. You are free to adjust these settings as needed.
     |
     | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
     |
     */
    'allowed_methods' => ['GET, POST, OPTIONS, PUT, DELETE'],

    'allowed_origins' => ['https://siskeu.wastu.digital, http://siskeu.wastu.digital'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['Origin, Content-Type, Accept, Authorization, X-Requested-With'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];