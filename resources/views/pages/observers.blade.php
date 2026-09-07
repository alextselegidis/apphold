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
    {{ __('observers') }}
@endsection

@section('breadcrumbs')
    @include('shared.breadcrumb', ['breadcrumbs' => [
        ['label' => __('observers')]
    ]])
@endsection

@section('navActions')
    <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#create-modal">
        <i class="bi bi-plus-square me-2"></i>
        {{ __('add') }}
    </a>
@endsection

@section('content')
    <!-- Search -->
    <form action="{{ route('observers') }}" method="GET" class="mb-4">
        <div class="input-group">
            <span class="input-group-text border-end-0">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text" id="q" name="q" class="form-control border-start-0 filter-search"
                   value="{{ $q }}"
                   placeholder="{{ __('search') }}...">
        </div>
    </form>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">
                                {!! sort_link('title', __('title')) !!}
                            </th>
                            <th>
                                {!! sort_link('url', __('url')) !!}
                            </th>
                            <th>{{ __('tags') }}</th>
                            <th class="pe-4 text-end" style="width: 100px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($observers as $observer)
                            <tr onclick="window.location='{{ route('observers.edit', $observer->id) }}'" style="cursor: pointer;">
                                <td class="ps-4">
                                    <span class="fw-medium">{{ Str::limit($observer->title, 40) }}</span>
                                </td>
                                <td>
                                    <a href="{{ $observer->url }}" target="_blank" class="text-decoration-none" onclick="event.stopPropagation();">
                                        {{ Str::limit($observer->formatted_url, 30) }}
                                        <i class="bi bi-box-arrow-up-right ms-1 small"></i>
                                    </a>
                                </td>
                                <td>
                                    @if($observer->tags->count())
                                        @foreach($observer->tags->take(3) as $tag)
                                            <span class="badge bg-dark">{{ $tag->name }}</span>
                                        @endforeach
                                        @if($observer->tags->count() > 3)
                                            <span class="badge bg-light">+{{ $observer->tags->count() - 3 }}</span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="dropdown" onclick="event.stopPropagation();">
                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            {{ __('actions') }}
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('observers.edit', ['observer' => $observer->id]) }}" class="dropdown-item">
                                                    <i class="bi bi-pencil me-2"></i>{{ __('edit') }}
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('observers.destroy', $observer->id) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('{{ __('delete_record_prompt') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-trash me-2"></i>{{ __('delete') }}
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if($observers->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                    {{ __('no_records_found') }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('modals.create-modal', ['route' => route('observers.store'), 'input_name' => 'url', 'input_type' => 'url'])

@endsection
