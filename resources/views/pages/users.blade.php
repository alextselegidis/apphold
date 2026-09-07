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
    {{ __('users') }}

@endsection

@section('breadcrumbs')
    @include('shared.breadcrumb', ['breadcrumbs' => [
        ['label' => __('setup'), 'url' => route('setup.localization')],
        ['label' => __('users')]
    ]])

@endsection

@section('navActions')
    <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#create-modal">
        <i class="bi bi-plus-square me-2"></i>
        {{ __('add') }}
    </a>

@endsection

@section('content')
    <div class="d-flex flex-column flex-lg-row gap-4">
        <!-- Sidebar -->
        <div class="flex-shrink-0" style="min-width: 200px;">
            @include('shared.setup-sidebar')
        </div>
        <!-- Main Content -->
        <div class="flex-grow-1">
            <h5 class="fw-bold mb-3">{{ __('users') }}</h5>
            <!-- Search -->
            <form action="{{ route('setup.users') }}" method="GET" class="mb-4">
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
                                    <th>
                                        {!! sort_link('email', __('email')) !!}
                                    </th>
                                    <th>
                                        {!! sort_link('role', __('role')) !!}
                                    </th>
                                    <th>
                                        {!! sort_link('is_active', __('active')) !!}
                                    </th>
                                    <th class="pe-4 text-end" style="width: 100px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr onclick="window.location='{{ route('setup.users.edit', $user->id) }}'" style="cursor: pointer;">
                                        <td class="ps-4">
                                            <span class="fw-medium">{{ $user->name }}</span>
                                        </td>
                                        <td>
                                            <a href="mailto:{{ $user->email }}" class="text-decoration-none" onclick="event.stopPropagation();">
                                                {{ $user->email }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ __($user->role) }}</span>
                                        </td>
                                        <td>
                                            @if($user->is_active)
                                                <span class="badge bg-success-subtle text-success">{{ __('yes') }}</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger">{{ __('no') }}</span>
                                            @endif
                                        </td>
                                        <td class="pe-4 text-end">
                                            <div class="dropdown" onclick="event.stopPropagation();">
                                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    {{ __('actions') }}
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a href="{{ route('setup.users.edit', ['user' => $user->id]) }}" class="dropdown-item">
                                                            <i class="bi bi-pencil me-2"></i>{{ __('edit') }}
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('setup.users.destroy', $user->id) }}"
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
                                @if($users->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-5">
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
        </div>
    </div>
    @include('modals.create-modal', ['route' => route('setup.users.store')])

@endsection
