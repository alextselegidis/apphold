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

namespace App\Enums;

namespace App\Enums;

use App\Traits\EnumValues;

enum RoleEnum: string
{
    use EnumValues;

    case ADMIN = 'admin';
    case USER = 'user';
}
