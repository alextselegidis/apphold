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
    {{__('dashboard')}}
@endsection

@section('navActions')
    <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#create-modal">
        <i class="bi bi-plus-square me-2"></i>
        {{__('add')}}
    </a>
@endsection

@section('content')
    <div class="row mb-3 mb-lg-0">
        <div class="col-lg-6">
            <form action="{{route('dashboard')}}" method="GET" class="mb-3">
                @csrf
                @method('GET')
                <div class="input-group mb-3">
                <span class="bg-body-tertiary input-group-text px-3">
                    <i class="bi bi-search"></i>
                </span>
                    <input type="text" id="q" name="q" class="form-control bg-body-tertiary border-start-0"
                           value="{{$q}}"
                           placeholder="{{__('search')}}" autofocus tabindex="-1" style="max-width: 300px;">
                </div>
            </form>

        </div>
        @php
            $toggleInactiveUrl = request()->fullUrlWithQuery([
                'show_inactive' => $showInactive ? 0 : 1,
            ]);
        @endphp

        <div class="col-lg-6 text-lg-end">

            {{-- PROJECT FILTER --}}

            <div class="d-lg-flex justify-content-lg-end gap-lg-4">

                @if($projectOptions->count())
                    <form method="GET" action="{{ route('dashboard') }}" class="d-inline-block">
                        <input type="hidden" name="q" value="{{ $q }}">
                        <input type="hidden" name="show_inactive" value="{{ $showInactive ? 1 : 0 }}">

                        <div class="d-flex gap-2">
                            <select class="form-select" name="project_id" onchange="this.form.submit()"
                                    style="width: 200px">
                                <option value="">{{ __('filter_by_project') }}</option>
                                @foreach($projectOptions as $projectOption)
                                    <option
                                        value="{{ $projectOption->value }}" {{ $selectedProjectId == $projectOption->value ? 'selected' : '' }}>
                                        {{ $projectOption->label }}
                                    </option>
                                @endforeach
                            </select>

                            @if($selectedProjectId)
                                <a href="{{ route('dashboard', ['q' => $q, 'show_inactive' => $showInactive]) }}"
                                   class="btn btn-outline-secondary">
                                    {{ __('clear') }}
                                </a>
                            @endif
                        </div>
                    </form>
                @endif

                <a href="{{ $toggleInactiveUrl }}"
                   class="btn {{$showInactive ? 'btn-primary' : 'btn-outline-primary'}} w-100 w-lg-auto">
                    {{ __($showInactive ? 'hide_inactive' : 'show_inactive') }}
                </a>
            </div>
        </div>
    </div>


    {{-- LIST OBSERVERS --}}

    @if ($observers->count())

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($observers as $observer)
                <div class="col">
                    <div class="card h-100 shadow-sm card-hover-move position-relative"
                         style="border-bottom: 5px solid {{ $observer->theme_color ?? '#dee2e6' }};">
                        @if ($observer->og_image || $observer->favicon)
                            <img src="data:image/x-icon;base64,{{ $observer->og_image ?: $observer->favicon }}"
                                 class="card-img-top bg-light p-2"
                                 alt="Favicon" style="width: 100%; height: 150px; object-fit: contain;">
                        @else
                            <img src="{{ url('images/logo.png') }}" class="card-img-top bg-light p-2"
                                 alt="Favicon" style="width: 100%; height: 150px; object-fit: contain;">
                        @endif

                        <div class="card-body">
                            <h6 class="card-title text-body">
                                {{ $observer->title ? Str::limit($observer->title, 100) : 'No Title' }}
                            </h6>
                            <p class="card-text text-truncate small">
                                <a href="{{ $observer->url }}" target="_blank"
                                   class="text-decoration-none stretched-observer">{{ $observer->formatted_url }}</a>
                            </p>

                            <div class="d-lg-flex justify-content-between">
                                @if ($observer->project)
                                    <div>
                                        <strong class="text-uppercase text-info small">
                                            <i class="bi bi-box me-1"></i>
                                            {{ $observer->project->name }}
                                        </strong>
                                    </div>
                                @endif

                                @if ($observer->tags()->count())
                                    <div class="mb-2">
                                        @foreach($observer->tags as $tag)
                                            <span class="badge bg-dark small">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer text-muted small d-flex">
                            {{ $observer->created_at->format('Y-m-d H:i') }}

                            <a href="{{route('observers.edit', ['observer' => $observer])}}" class="ms-auto">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <a href="{{route('observers.toggle', ['observer' => $observer])}}" class="ms-3">
                                <i class="bi bi-{{$observer->is_active ? 'circle-fill' : 'circle'}} text-success"></i>
                            </a>

                            <form action="{{route('observers.destroy', $observer->id)}}"
                                  method="POST"
                                  class="ms-3"
                                  onsubmit="return confirm('{{__('delete_record_prompt')}}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="border-0 bg-transparent observer p-0 text-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center my-5 py-5">
            <div class="mb-5">
                <i class="bi bi-search display-1 text-primary"></i>
            </div>
            <h1>
                {{__('no_records_found')}}
            </h1>
        </div>

    @endif

    @if ($length)
        <div class="text-center mt-4">
            <a href="{{ request()->fullUrlWithQuery(['length' => $length + 25]) }}" class="btn btn-outline-primary">
                {{ __('show_more') }}
            </a>
        </div>
    @endif

    @include('modals.create-modal', ['route' => route('observers.store'), 'title' => __('add'), 'input_name' => 'url', 'input_type' => 'url'])

@endsection

