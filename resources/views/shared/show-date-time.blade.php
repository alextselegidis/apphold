{{--
/* ----------------------------------------------------------------------------
 * Apphold - Online Software Telemetry
 *
 * @package     Apphold
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://github.com/alextselegidis/apphold
 * ---------------------------------------------------------------------------- */
--}}

<div class="mb-4">
    <h6 class="text-muted">
        {{$label}}
    </h6>

    @include('shared.date-time-value', ['value' => $value])
</div>
