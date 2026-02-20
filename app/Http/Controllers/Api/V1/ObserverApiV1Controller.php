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

use App\Models\Observer;

class ObserverApiV1Controller extends BaseApiV1Controller
{
    protected $model = Observer::class;

    public function includes(): array
    {
        return ['user', 'tags'];
    }

    public function filterableBy(): array
    {
        return ['title', 'url', 'is_active', 'user_id', 'created_at', 'updated_at'];
    }

    public function sortableBy(): array
    {
        return ['id', 'title', 'url', 'is_active', 'created_at', 'updated_at'];
    }

    public function searchableBy(): array
    {
        return ['title', 'url', 'notes'];
    }
}
