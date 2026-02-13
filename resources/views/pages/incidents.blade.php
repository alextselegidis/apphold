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
    {{ __('incidents') }}
@endsection

@section('breadcrumbs')
    @include('shared.breadcrumb', ['breadcrumbs' => [
        ['label' => __('incidents')]
    ]])
@endsection

@section('content')
    <!-- Search and Filter -->
    <form action="{{ route('incidents') }}" method="GET" class="mb-4">
        <div class="d-flex flex-wrap gap-3">
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text bg-light border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" id="q" name="q" class="form-control bg-light border-start-0"
                       value="{{ $q }}"
                       placeholder="{{ __('search') }}...">
            </div>
            <div class="input-group" style="max-width: 180px;">
                <select name="type" class="form-select" onchange="this.form.submit()">
                    <option value="">{{ __('all_types') }}</option>
                    @foreach($types as $t)
                        <option value="{{ $t }}" {{ $type === $t ? 'selected' : '' }}>{{ __($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="input-group" style="max-width: 180px;">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">{{ __('all_statuses') }}</option>
                    @foreach($statuses as $s)
                        <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>{{ __($s) }}</option>
                    @endforeach
                </select>
            </div>
            @if($q || $type || $status)
                <a href="{{ route('incidents') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i>{{ __('clear') }}
                </a>
            @endif
        </div>
    </form>
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
            <!-- Table -->
            <div class="table-responsive" style="overflow: visible;">
                <table class="table table-striped table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="border-0 ps-4">
                                <a href="{{ route('incidents', ['sort' => 'created_at', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc', 'type' => $type, 'status' => $status, 'q' => $q]) }}" class="text-decoration-none text-white">
                                    {{ __('date') }}
                                    @if(request('sort', 'created_at') === 'created_at')
                                        <i class="bi bi-chevron-{{ request('direction', 'desc') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="border-0">
                                <a href="{{ route('incidents', ['sort' => 'type', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc', 'type' => $type, 'status' => $status, 'q' => $q]) }}" class="text-decoration-none text-white">
                                    {{ __('type') }}
                                    @if(request('sort') === 'type')
                                        <i class="bi bi-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="border-0">
                                <a href="{{ route('incidents', ['sort' => 'status', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc', 'type' => $type, 'status' => $status, 'q' => $q]) }}" class="text-decoration-none text-white">
                                    {{ __('status') }}
                                    @if(request('sort') === 'status')
                                        <i class="bi bi-chevron-{{ request('direction') === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="border-0 text-white">{{ __('observer') }}</th>
                            <th class="border-0 text-white">{{ __('message') }}</th>
                            <th class="border-0 pe-4 text-end" style="width: 100px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($incidents as $incident)
                            <tr onclick="window.location='{{ route('incidents.edit', $incident->id) }}'" style="cursor: pointer;">
                                <td class="border-0 ps-4">
                                    <span class="text-muted small">{{ $incident->created_at->locale(setting('default_locale', 'en'))->timezone(setting('default_timezone', 'UTC'))->isoFormat('L LT') }}</span>
                                </td>
                                <td class="border-0">
                                    <span class="badge bg-danger">{{ __($incident->type) }}</span>
                                </td>
                                <td class="border-0">
                                    @php
                                        $statusColors = [
                                            'new' => 'primary',
                                            'ignored' => 'secondary',
                                            'fixing' => 'warning',
                                            'fixed' => 'success',
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$incident->status] ?? 'secondary' }}">{{ __($incident->status) }}</span>
                                </td>
                                <td class="border-0">
                                    @if($incident->observer)
                                        <a href="{{ route('observers.edit', $incident->observer->id) }}" class="text-decoration-none" onclick="event.stopPropagation();">
                                            {{ Str::limit($incident->observer->title ?: $incident->observer->url, 30) }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="border-0">
                                    <span class="text-muted">{{ Str::limit($incident->message, 50) }}</span>
                                </td>
                                <td class="border-0 pe-4 text-end">
                                    <div class="dropdown" onclick="event.stopPropagation();">
                                        <button class="btn btn-sm btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            {{ __('actions') }}
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('incidents.edit', $incident->id) }}" class="dropdown-item">
                                                    <i class="bi bi-pencil me-2"></i>{{ __('edit') }}
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('incidents.destroy', $incident->id) }}"
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
                        @if($incidents->isEmpty())
                            <tr>
                                <td colspan="6" class="border-0 text-center text-muted py-5">
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

@endsection
