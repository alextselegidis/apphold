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

use App\Models\User;
use Orion\Http\Controllers\Controller;

class UserApiV1Controller extends Controller
{
    protected $model = User::class;

    public function filterableBy(): array
    {
        return ['name', 'email', 'role', 'is_active', 'created_at', 'updated_at'];
    }

    public function sortableBy(): array
    {
        return ['id', 'name', 'email', 'role', 'is_active', 'created_at', 'updated_at'];
    }

    public function searchableBy(): array
    {
        return ['name', 'email'];
    }
}
