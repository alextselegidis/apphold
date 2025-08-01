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

    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <base href="{{url('')}}/">

    <title>@yield('pageTitle') | Apphold</title>
    <meta name="description" content="Apphold is a bookmark management application designed to help users easily organize your web links in one place.">
    <meta name="theme-color" content="#e3434c">

    <link rel="icon" href="favicon.ico" type="image/x-icon" />

    <link rel="stylesheet" href="vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/bootstrap-icons/bootstrap-icons.min.css">
    <link rel="stylesheet" href="vendor/choices.js/choices.min.css">
    <link rel="stylesheet" href="vendor/pace-js/pace-theme-default.min.css">
    <link rel="stylesheet" href="vendor/pace-js/pace-theme-flat-top.tmpl.css">
    <link rel="stylesheet" href="styles/apphold.css?{{config('app.version')}}">

    @yield('styles')
</head>
<body class="main-layout d-flex flex-column h-100">
<div class="flex-shrink-0">
    @include('shared.header')

    <!-- Page Heading -->

    @hasSection('pageTitle')
        <header class="bg-body-secondary mb-3">
            <div class="container">
                <div class="row">

                    <nav class="navbar navbar-expand-lg">
                        <div class="container-fluid">
                            <span class="navbar-brand fw-bolder text-body-secondary">
                                @if(View::hasSection('navTitle'))
                                    @yield('navTitle')
                                @elseif(View::hasSection('pageTitle'))
                                    @yield('pageTitle')
                                @endif
                            </span>

                            @hasSection('navActions')
                                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#page-navbar-actions">
                                    <span class="navbar-toggler-icon"></span>
                                </button>

                                <div class="collapse navbar-collapse" id="page-navbar-actions">
                                    <nav class="navbar-nav ms-lg-auto mb-2 mb-lg-0">
                                        @yield('navActions')
                                    </nav>
                                </div>
                            @endif
                        </div>
                    </nav>

                </div>
            </div>
        </header>
    @endif

    <!-- Page Content -->
    <main class="container">
        <div class="row">
            <div class="col">
                @yield('content')
            </div>
        </div>
    </main>

    <!-- Toast Container (Bottom Right using Bootstrap classes) -->
    <div class="toast-container position-fixed bottom-0 end-0 mb-5 p-3">

        <!-- Success Toast -->
        @if (session('success'))
            <div class="toast align-items-center text-bg-success border-0 show mb-2" role="alert" aria-live="assertive"
                 aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                </div>
            </div>
        @endif

        <!-- Error Toast -->
        @if (session('error'))
            <div class="toast align-items-center text-bg-danger border-0 show" role="alert" aria-live="assertive"
                 aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                            aria-label="Close"></button>
                </div>
            </div>
        @endif

    </div>

</div>

@include('shared.footer')


<script src="vendor/bootstrap/bootstrap.bundle.min.js"></script>
<script src="vendor/choices.js/choices.min.js"></script>
<script src="vendor/pace-js/pace.min.js"></script>
<script src="scripts/apphold.js?{{config('app.version')}}"></script>

@yield('scripts')
</body>
</html>
