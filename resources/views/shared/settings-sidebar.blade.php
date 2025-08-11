{{--
/* ----------------------------------------------------------------------------
 * Apphold - Online Software Telemetry
 *
 * @package     Apphold
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://apphold.org
 * ---------------------------------------------------------------------------- */
--}}

<h1 class="fs-3 mb-4 text-muted">
    {{__('menu')}}
</h1>

<ul id="settings-nav" class="nav flex-column fw-bold fs-5 sidebar-width">
    <li class="nav-item mb-3">
        <a class="nav-link px-0 py-2 d-flex align-items-center gap-3 text-primary" href="{{ route('settings') }}">
            <i class="bi bi-gear fs-4 text-muted"></i>
            {{__('general')}}
        </a>
    </li>

    <li class="nav-item mb-3">
        <a class="nav-link px-0 py-2 d-flex align-items-center gap-3 text-primary" href="{{ route('observers') }}">
            <i class="bi bi-eye fs-4 text-muted"></i>
            {{__('observers')}}
        </a>
    </li>

    <li class="nav-item mb-3">
        <a class="nav-link px-0 py-2 d-flex align-items-center gap-3 text-primary" href="{{ route('tags') }}">
            <i class="bi bi-tags fs-4 text-muted"></i>
            {{__('tags')}}
        </a>
    </li>

    <li class="nav-item mb-3">
        <a class="nav-link px-0 py-2 d-flex align-items-center gap-3 text-primary" href="{{ route('projects') }}">
            <i class="bi bi-box fs-4 text-muted"></i>
            {{__('projects')}}
        </a>
    </li>

    <li class="nav-item mb-3">
        <a class="nav-link px-0 py-2 d-flex align-items-center gap-3 text-primary" href="{{ route('users') }}">
            <i class="bi bi-people fs-4 text-muted"></i>
            {{__('users')}}
        </a>
    </li>
</ul>
