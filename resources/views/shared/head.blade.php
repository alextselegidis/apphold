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

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">

<base href="{{url('')}}/">

<title>@yield('pageTitle') | Apphold</title>
<meta name="description" content="Apphold is a software telemetry application designed to help users easily monitor their applications.">
<meta name="theme-color" content="#976fd5">

<link rel="icon" href="favicon.ico" type="image/x-icon" />

<link rel="manifest" href="manifest.webmanifest">
<link rel="apple-touch-icon" href="apple-touch-icon.png">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="Apphold">

<link rel="stylesheet" href="vendor/bootstrap/bootstrap.min.css">
<link rel="stylesheet" href="vendor/bootstrap-icons/bootstrap-icons.min.css">

<link rel="stylesheet" href="vendor/pace-js/pace-theme-default.min.css">
<link rel="stylesheet" href="vendor/pace-js/pace-theme-flat-top.tmpl.css">

<link rel="stylesheet" href="styles/apphold.css?{{config('app.version')}}">

@yield('styles')
