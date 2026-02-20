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

use App\Models\Incident;

class IncidentApiV1Controller extends BaseApiV1Controller
{
    protected $model = Incident::class;

    public function includes(): array
    {
        return ['user', 'observer', 'assignedUser', 'comments'];
    }

    public function filterableBy(): array
    {
        return ['type', 'status', 'user_id', 'observer_id', 'assigned_user_id', 'status_code', 'created_at', 'updated_at'];
    }

    public function sortableBy(): array
    {
        return ['id', 'type', 'status', 'status_code', 'created_at', 'updated_at'];
    }

    public function searchableBy(): array
    {
        return ['message'];
    }
}
