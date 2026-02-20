<?php

/* ----------------------------------------------------------------------------
 * Apphold - Online Software Telemetry
 *
 * @package     Apphold
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://apphold.org
 * ---------------------------------------------------------------------------- */

return [
    /*
    |--------------------------------------------------------------------------
    | Auth Guard
    |--------------------------------------------------------------------------
    |
    | This value is the name of the guard that will be used to authenticate
    | requests to the API. By default, Orion uses the "api" guard. Since we
    | are using Sanctum, we set this to "sanctum".
    |
    */
    'auth' => [
        'guard' => 'sanctum',
    ],

    /*
    |--------------------------------------------------------------------------
    | Namespaces
    |--------------------------------------------------------------------------
    |
    | These are the namespaces for Orion resources.
    |
    */
    'namespaces' => [
        'models' => 'App\\Models\\',
        'controllers' => 'App\\Http\\Controllers\\Api\\',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | Default pagination settings for API responses.
    |
    */
    'pagination' => [
        'default_limit' => 15,
        'max_limit' => 100,
    ],

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    |
    | Search configuration.
    |
    */
    'search' => [
        'case_sensitive' => false,
    ],
];
