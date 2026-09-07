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
    {{ __('tags') }}

@endsection

@section('breadcrumbs')
    @include('shared.breadcrumb', ['breadcrumbs' => [
        ['label' => __('tags')]
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
    <form action="{{ route('tags') }}" method="GET" class="mb-4">
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
                                {!! sort_link('name', __('name')) !!}
                            </th>
                            <th>{{ __('count') }}</th>
                            <th class="pe-4 text-end" style="width: 100px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tags as $tag)
                            <tr onclick="window.location='{{ route('tags.edit', $tag->id) }}'" style="cursor: pointer;">
                                <td class="ps-4">
                                    <span class="fw-medium">{{ $tag->name }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $tag->count }}</span>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="dropdown" onclick="event.stopPropagation();">
                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            {{ __('actions') }}
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('tags.edit', ['tag' => $tag->id]) }}" class="dropdown-item">
                                                    <i class="bi bi-pencil me-2"></i>{{ __('edit') }}
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('tags.destroy', $tag->id) }}"
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
                        @if($tags->isEmpty())
                            <tr>
                                <td colspan="3" class="text-center text-muted py-5">
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
    @include('modals.create-modal', ['route' => route('tags.store')])

@endsection
