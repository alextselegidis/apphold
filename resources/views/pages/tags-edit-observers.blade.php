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
    {{ $tag->name }}

@endsection

@section('breadcrumbs')
    @include('shared.breadcrumb', ['breadcrumbs' => [
        ['label' => __('tags'), 'url' => route('tags')],
        ['label' => $tag->name]
    ]])

@endsection

@section('navActions')
    <a href="#" class="nav-link me-lg-3" data-bs-toggle="modal" data-bs-target="#create-modal">
        <i class="bi bi-plus-square me-2"></i>
        {{ __('add') }}
    </a>
    <form action="{{ route('tags.destroy', $tag->id) }}"
          method="POST"
          onsubmit="return confirm('{{ __('delete_record_prompt') }}')">
        @csrf
        @method('DELETE')
        <button type="submit" class="nav-link">
            <i class="bi bi-trash me-2"></i>
            {{ __('delete') }}
        </button>
    </form>

@endsection

@section('content')
    <div class="d-flex flex-column flex-lg-row gap-4">
        <!-- Sidebar -->
        <div class="flex-shrink-0" style="min-width: 200px;">
            @include('shared.edit-sidebar', ['items' => [
                ['label' => __('details'), 'route' => 'tags.edit', 'params' => ['tag' => $tag->id], 'icon' => 'tag'],
                ['label' => __('observers'), 'route' => 'tags.observers', 'params' => ['tag' => $tag->id], 'icon' => 'eye']
            ]])
        </div>
        <!-- Main Content -->
        <div class="flex-grow-1">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    @if($observers->count())
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-muted small fw-medium">{{ __('title') }}</th>
                                        <th class="text-muted small fw-medium">{{ __('url') }}</th>
                                        <th class="text-muted small fw-medium">{{ __('active') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($observers as $observer)
                                        <tr style="cursor: pointer;" onclick="window.location='{{ route('observers.edit', $observer->id) }}'">
                                            <td>{{ $observer->title ?: '-' }}</td>
                                            <td>{{ Str::limit($observer->url, 50) }}</td>
                                            <td>
                                                @if($observer->is_active)
                                                    <span class="badge bg-success">{{ __('yes') }}</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ __('no') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">{{ __('no_records_found') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('modals.create-modal', ['route' => route('tags.store')])

@endsection
