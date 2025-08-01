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

@extends('layouts.main-layout')

@section('pageTitle')
    {{__('About')}}
@endsection

@section('content')

    <div class="mx-auto my-5 text-center" style="max-width: 600px">
        <div class="mb-5">
            <img src="images/logo.png" alt="Logo" class="me-2" style="height: 128px">
        </div>

        <h1 class="fs-3 mb-5">
            Apphold <span class="text-muted">v{{config('app.version')}}</span>
        </h1>

        <div class="mb-5">
            Apphold is an open-source software telemetry platform that monitors the uptime of your websites in real
            time. Designed for developers and teams who need reliable site availability tracking, Apphold checks your
            configured URLs at regular intervals and automatically detects outages. When a site goes down, Apphold logs
            the incident, notifies your team, and continues checking until it's resolved—capturing both the start and
            end times for accurate reporting. With project-based organization and user-level access control, Apphold
            makes it simple to manage multiple sites across different teams or clients.
        </div>

        <div>
            <a href="https://apphold.org" class="btn btn-outline-primary" target="_blank">Official Website</a>
        </div>
    </div>
@endsection

