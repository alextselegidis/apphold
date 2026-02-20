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

use App\Models\IncidentComment;

class IncidentCommentApiV1Controller extends BaseApiV1Controller
{
    protected $model = IncidentComment::class;

    public function includes(): array
    {
        return ['incident', 'user'];
    }

    public function filterableBy(): array
    {
        return ['incident_id', 'user_id', 'created_at', 'updated_at'];
    }

    public function sortableBy(): array
    {
        return ['id', 'created_at', 'updated_at'];
    }

    public function searchableBy(): array
    {
        return ['content'];
    }
}
