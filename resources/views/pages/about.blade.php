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
    {{__('about')}}

@endsection

@section('breadcrumbs')
    @include('shared.breadcrumb', ['breadcrumbs' => [
        ['label' => __('about')]
    ]])

@endsection

@section('content')
    <div>
        <div class="mx-auto my-5 text-center" style="max-width: 600px">
            <div class="mb-5">
                <img src="images/logo.png" alt="Logo" class="me-2" style="height: 128px">
            </div>
            <h1 class="fs-3 mb-5">
                Apphold <span class="text-muted">v{{config('app.version')}}</span>
            </h1>
            <div class="mb-5 text-secondary">
                Apphold is an open-source software telemetry application that helps you monitor, track, and
                manage your web applications in one streamlined place. Monitor uptime, track performance, and
                receive notifications with a clean and intuitive interface, keeping your applications healthy and reliable.
            </div>
            <div class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                <a href="https://github.com/alextselegidis/apphold" class="btn btn-outline-primary btn-equal-width" target="_blank">
                    <i class="bi bi-github me-2"></i>
                    GitHub
                </a>
                <a href="https://alextselegidis.com" class="btn btn-outline-secondary btn-equal-width" target="_blank">
                    <img src="images/alextselegidis-logo-16x16.png" alt="logo" class="me-2"/>
                    alextselegidis.com
                </a>
            </div>

            @if(!empty($posts))
                <hr class="my-5">

                <div class="mb-4 text-start">
                    <h2 class="fs-4 mb-3 text-center">
                        <i class="bi bi-journal-text me-2"></i>
                        {{ __('latest_blog_posts') }}
                    </h2>

                    <div class="list-group mb-3">
                        @foreach($posts as $post)
                            <a href="{{ $post['link'] }}" target="_blank"
                               class="list-group-item list-group-item-action py-3">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="fw-semibold mb-1">
                                            {{ $post['title'] }}
                                        </div>
                                        <div class="small text-secondary mb-2">
                                            {{ $post['published_at']->locale(app()->getLocale())->isoFormat('LL') }}
                                        </div>
                                        <div class="small text-secondary">
                                            {{ \Illuminate\Support\Str::limit($post['description'], 160) }}
                                        </div>
                                    </div>
                                    <i class="bi bi-box-arrow-up-right text-secondary"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="text-center">
                        <a href="https://apphold.org/blog" target="_blank" class="btn btn-outline-primary">
                            <i class="bi bi-journals me-2"></i>
                            {{ __('visit_the_blog') }}
                        </a>
                    </div>
                </div>
            @endif

            <hr class="my-5">

            <div class="mb-4">
                <h2 class="fs-4 mb-3">
                    <i class="bi bi-star text-warning me-2"></i>
                    {{ __('premium') }}
                </h2>
                <div class="text-secondary mb-4">
                    {{ __('premium_description') }}
                </div>
                <a href="https://apphold.org/premium" class="btn btn-primary btn-lg w-100" target="_blank">
                    <i class="bi bi-star me-2"></i>
                    {{ __('go_premium') }}
                </a>
            </div>
        </div>
    </div>

@endsection
