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

namespace App\Auth;

use Illuminate\Auth\SessionGuard;

class AppSessionGuard extends SessionGuard
{
    /**
     * Get the name of the cookie used to store the "recaller".
     *
     * The default Laravel implementation returns `remember_<guard>_<sha1(SessionGuard::class)>`,
     * which is identical for every Laravel install. When two installs share a domain, the
     * "remember me" cookie set by one would clobber/log out the other, so the cookie name
     * is suffixed with a hash that is unique per installation.
     */
    public function getRecallerName()
    {
        return parent::getRecallerName().'_'.app_instance_id();
    }
}
