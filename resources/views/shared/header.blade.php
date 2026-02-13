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

<div class="bg-primary">
    <div class="container">
        <div class="row">
            <div class="col">
                <nav class="navbar navbar-expand-lg">
                    <a class="navbar-brand d-flex align-items-center p-0 m-0" href="{{ route('dashboard') }}">
                        <img src="images/logo.png" alt="Logo" class="me-2" style="height: 32px">
                        <strong class="fs-4 text-white">APPHOLD</strong>
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#top-nav"
                            aria-controls="top-nav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse ms-md-4" id="top-nav">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <!-- Dashboard -->
                            <li class="nav-item">
                                <a class="nav-link nav-menu-item text-white py-lg-4 {{ request()->routeIs('dashboard') ? 'fw-bold' : '' }}" href="{{ route('dashboard') }}">
                                    <i class="bi bi-house me-2"></i>
                                    {{ __('dashboard') }}
                                </a>
                            </li>
                            <!-- Observers -->
                            <li class="nav-item">
                                <a class="nav-link nav-menu-item text-white py-lg-4 {{ request()->routeIs('observers*') ? 'fw-bold' : '' }}" href="{{ route('observers') }}">
                                    <i class="bi bi-eye me-2"></i>
                                    {{ __('observers') }}
                                </a>
                            </li>
                            <!-- Tags -->
                            <li class="nav-item">
                                <a class="nav-link nav-menu-item text-white py-lg-4 {{ request()->routeIs('tags*') ? 'fw-bold' : '' }}" href="{{ route('tags') }}">
                                    <i class="bi bi-tags me-2"></i>
                                    {{ __('tags') }}
                                </a>
                            </li>
                            <!-- Incidents -->
                            <li class="nav-item">
                                <a class="nav-link nav-menu-item text-white py-lg-4 {{ request()->routeIs('incidents*') ? 'fw-bold' : '' }}" href="{{ route('incidents') }}">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    {{ __('incidents') }}
                                </a>
                            </li>
                        </ul>
                        <!-- Global Search -->
                        <form action="{{ route('dashboard') }}" method="GET" class="d-flex me-lg-3 my-2 my-lg-0">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0">
                                    <i class="bi bi-search text-muted"></i>
                                </span>
                                <input type="text" name="q" class="form-control border-start-0"
                                       value="{{ request()->routeIs('dashboard') ? request('q') : '' }}"
                                       placeholder="{{ __('search') }} {{ strtolower(__('observers')) }}..."
                                       style="min-width: 150px;">
                            </div>
                        </form>
                        <!-- Account Dropdown -->
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                    <i class="bi bi-person me-1"></i>
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @if(Auth::user()->isAdmin())
                                        <li>
                                            <a class="dropdown-item {{ request()->routeIs('setup.*') ? 'active' : '' }}" href="{{ route('setup.localization') }}">
                                                <i class="bi bi-gear me-2"></i>{{ __('setup') }}
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                    @endif
                                    <li>
                                        <a class="dropdown-item" href="{{ route('account') }}">
                                            <i class="bi bi-person-circle me-2"></i>{{ __('account') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('about') }}">
                                            <i class="bi bi-info-circle me-2"></i>{{ __('about') }}
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout.perform') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-box-arrow-right me-2"></i>{{ __('logout') }}
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>
