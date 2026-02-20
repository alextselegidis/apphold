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

namespace App\Http\Controllers\Api\V1;

use Orion\Http\Controllers\Controller;

abstract class BaseApiV1Controller extends Controller
{
    /**
     * The guard used for authentication.
     */
    protected function guard(): string
    {
        return 'sanctum';
    }
}
