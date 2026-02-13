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

@section('breadcrumbs')
    @include('shared.breadcrumb', ['breadcrumbs' => [
        ['label' => __('dashboard')]
    ]])
@endsection

@section('navActions')
    <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#create-modal">
        <i class="bi bi-plus-square me-2"></i>
        {{__('add')}}
    </a>
@endsection

@section('content')
    <div>
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
                {{-- TAG FILTER --}}
                <div class="d-lg-flex justify-content-lg-end gap-lg-4 align-items-center">
                    @if($tags->count())
                        <div class="d-flex gap-2 mb-3 mb-lg-0">
                            <div class="dropdown flex-grow-1 flex-lg-grow-0">
                                <button class="btn {{ $selectedTagId ? 'btn-primary' : 'btn-outline-primary' }} dropdown-toggle w-100 w-lg-auto" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-tag me-1"></i>
                                    @if($selectedTagId)
                                        {{ $tags->firstWhere('id', $selectedTagId)?->name ?? __('filter_by_tag') }}
                                    @else
                                        {{ __('filter_by_tag') }}
                                    @endif
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end w-100" style="max-height: 300px; overflow-y: auto;">
                                    @foreach($tags as $tag)
                                        <li>
                                            <a class="dropdown-item {{ $selectedTagId == $tag->id ? 'active' : '' }}"
                                               href="{{ route('dashboard', ['q' => $q, 'show_inactive' => $showInactive ? 1 : 0, 'tag_id' => $tag->id]) }}">
                                                {{ $tag->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            @if($selectedTagId)
                                <a href="{{ route('dashboard', ['q' => $q, 'show_inactive' => $showInactive]) }}"
                                   class="btn btn-primary">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            @endif
                        </div>
                    @endif
                    <a href="{{ $toggleInactiveUrl }}"
                       class="btn {{ $showInactive ? 'btn-primary' : 'btn-outline-primary' }} w-100 w-lg-auto">
                        <i class="bi bi-eye me-1"></i>
                        {{ __('inactive') }}
                    </a>
                </div>
            </div>
        </div>
        {{-- LIST OBSERVERS --}}
        @if ($observers->count())
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($observers as $observer)
                    <div class="col">
                        <div class="card h-100 shadow-sm card-hover-move d-flex flex-column {{ !$observer->is_active ? 'bg-opacity-10 bg-warning' : '' }}"
                             style="border-bottom: 5px solid #dee2e6;"
                             data-bs-toggle="tooltip"
                             data-bs-placement="top"
                             data-bs-title="{{ $observer->title ?: 'No Title' }}">
                            <a href="{{ $observer->url }}" target="_blank" class="text-decoration-none">
                                @if ($observer->og_image)
                                    <img src="data:image/x-icon;base64,{{ $observer->og_image }}"
                                         class="card-img-top"
                                         alt="Preview" style="width: 100%; height: 150px; object-fit: cover;">
                                @elseif ($observer->favicon)
                                    <img src="data:image/x-icon;base64,{{ $observer->favicon }}"
                                         class="card-img-top p-4"
                                         alt="Favicon" style="width: 100%; height: 150px; object-fit: contain;">
                                @else
                                    <img src="{{ url('images/logo.png') }}" class="card-img-top p-4"
                                         alt="Favicon" style="width: 100%; height: 150px; object-fit: contain;">
                                @endif
                            </a>
                            <a href="{{ $observer->url }}" target="_blank" class="text-decoration-none flex-grow-1 d-flex flex-column">
                                <div class="card-body d-flex flex-column flex-grow-1">
                                    <h6 class="card-title text-body">
                                        {{ $observer->title ? Str::limit($observer->title, 50) : 'No Title' }}
                                    </h6>
                                    <p class="card-text text-truncate small" style="color: #0d6efd;">
                                        {{ $observer->formatted_url }}
                                    </p>
                                    <div class="mt-auto" style="min-height: 24px;">
                                        @if ($observer->tags()->count())
                                            @foreach($observer->tags as $tag)
                                                <span class="badge bg-dark">
                                                    {{ $tag->name }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </a>
                            <div class="card-footer bg-body-secondary text-muted small d-flex align-items-center mt-auto">
                                {{ $observer->created_at->locale(app()->getLocale())->isoFormat('L LT') }}
                                <a href="{{ route('observers.edit', ['observer' => $observer]) }}" class="ms-auto" title="{{ __('edit') }}">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('observers.toggle', ['observer' => $observer]) }}" class="ms-3" title="{{ __($observer->is_active ? 'deactivate' : 'activate') }}">
                                    <i class="bi bi-{{ $observer->is_active ? 'toggle-on' : 'toggle-off' }}"></i>
                                </a>
                                <form action="{{ route('observers.destroy', $observer->id) }}"
                                      method="POST"
                                      class="ms-3"
                                      onsubmit="return confirm('{{ __('delete_record_prompt') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="border-0 bg-transparent p-0 text-danger" title="{{ __('delete') }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            {{-- Load More --}}
            @if ($length)
                <div class="text-center mt-4">
                    <a href="{{ route('dashboard', ['q' => $q, 'show_inactive' => $showInactive, 'length' => $length + 25]) }}"
                       class="btn btn-outline-primary">
                        <i class="bi bi-arrow-down-circle me-1"></i>
                        {{ __('load_more') }}
                    </a>
                </div>
            @endif
        @else
            <div class="text-center text-muted py-5">
                <i class="bi bi-inbox display-4 d-block mb-3"></i>
                {{ __('no_records_found') }}
            </div>

        @endif
    </div>
    @include('modals.create-modal', ['route' => route('observers.store'), 'input_name' => 'url', 'input_type' => 'url'])

@endsection
