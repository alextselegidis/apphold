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

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken as Middleware;

class ValidateCsrfToken extends Middleware
{
    /**
     * The "XSRF-TOKEN" cookie name is hardcoded by the framework, so two Laravel installs
     * sharing a domain overwrite each other's cookie. Nothing in Apphold reads it (all
     * forms use the @csrf field), so the cookie is simply not set at all.
     *
     * @var bool
     */
    protected $addHttpCookie = false;
}
