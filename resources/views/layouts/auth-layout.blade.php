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
    <meta name="description" content="Apphold is a software telemetry application designed to help users easily monitor their applications.">
    <meta name="theme-color" content="#e3434c">
    <link rel="icon" href="favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/bootstrap-icons/bootstrap-icons.min.css">
    <link rel="stylesheet" href="vendor/pace-js/pace-theme-default.min.css">
    <link rel="stylesheet" href="vendor/pace-js/pace-theme-flat-top.tmpl.css">
    <link rel="stylesheet" href="styles/apphold.css?{{config('app.version')}}">
    @yield('styles')
</head>
<body class="bg-light auth-layout">
<div class="d-flex justify-content-center align-items-lg-center min-vh-100">
    <div class="bg-white w-100 rounded-0 rounded-lg-3 shadow-lg-sm py-lg-4 min-vh-100 min-vh-lg-auto d-flex flex-column justify-content-center d-lg-block" style="max-width: 500px;">
        <div class="text-center mt-3 mt-lg-5 mb-3">
            <img src="images/logo.png" alt="logo" class="public-logo-image" style="width: 128px"/>
        </div>

        @yield('content')
        <div class="text-center small my-5">
            Powered By
            <a href="https://apphold.org" target="_blank">
                Apphold
            </a>
        </div>
    </div>
</div>
<script src="vendor/bootstrap/bootstrap.bundle.min.js"></script>
<script src="vendor/pace-js/pace.min.js"></script>
<script src="scripts/apphold.js?{{config('app.version')}}"></script>
@yield('scripts')
</body>
</html>
