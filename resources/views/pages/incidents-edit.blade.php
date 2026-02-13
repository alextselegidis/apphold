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
    {{ __('incident') }} #{{ $incident->id }}

@endsection

@section('breadcrumbs')
    @include('shared.breadcrumb', ['breadcrumbs' => [
        ['label' => __('incidents'), 'url' => route('incidents')],
        ['label' => __('incident') . ' #' . $incident->id]
    ]])

@endsection

@section('navActions')
    <form action="{{ route('incidents.destroy', $incident->id) }}"
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
                ['label' => __('details'), 'route' => 'incidents.edit', 'params' => ['incident' => $incident->id], 'icon' => 'file-text'],
                ['label' => __('comments'), 'route' => 'incidents.comments', 'params' => ['incident' => $incident->id], 'icon' => 'chat-dots']
            ]])
        </div>
        <!-- Main Content -->
        <div class="flex-grow-1">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <!-- Incident Info -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>{{ __('type') }}:</strong>
                                    <span class="badge bg-danger">{{ __($incident->type) }}</span>
                                </p>
                                <p class="mb-1"><strong>{{ __('observer') }}:</strong>
                                    @if($incident->observer)
                                        <a href="{{ route('observers.edit', $incident->observer->id) }}">
                                            {{ $incident->observer->title ?: $incident->observer->url }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </p>
                                <p class="mb-0"><strong>{{ __('message') }}:</strong>
                                    {{ $incident->message ?: '-' }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>{{ __('date') }}:</strong>
                                    {{ $incident->created_at->locale(setting('default_locale', 'en'))->timezone(setting('default_timezone', 'UTC'))->isoFormat('LLLL') }}
                                </p>
                                @if($incident->status_code)
                                    <p class="mb-0"><strong>{{ __('status_code') }}:</strong>
                                        {{ $incident->status_code }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('incidents.update', ['incident' => $incident->id]) }}" method="POST" id="edit-form">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label text-dark small fw-medium">
                                        <span class="text-danger">*</span> {{ __('status') }}
                                    </label>
                                    <select id="status" name="status" class="form-select" required>
                                        <option value="new" {{ old('status', $incident->status) === 'new' ? 'selected' : '' }}>{{ __('new') }}</option>
                                        <option value="ignored" {{ old('status', $incident->status) === 'ignored' ? 'selected' : '' }}>{{ __('ignored') }}</option>
                                        <option value="fixing" {{ old('status', $incident->status) === 'fixing' ? 'selected' : '' }}>{{ __('fixing') }}</option>
                                        <option value="fixed" {{ old('status', $incident->status) === 'fixed' ? 'selected' : '' }}>{{ __('fixed') }}</option>
                                    </select>
                                    @error('status')
                                    <span class="form-text text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="assigned_user_id" class="form-label text-dark small fw-medium">
                                        {{ __('assigned_to') }}
                                    </label>
                                    <select id="assigned_user_id" name="assigned_user_id" class="form-select">
                                        <option value="">{{ __('unassigned') }}</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('assigned_user_id', $incident->assigned_user_id) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('assigned_user_id')
                                    <span class="form-text text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Card Footer with Save Button -->
                <div class="card-footer bg-body-secondary border-top text-end py-3 px-4">
                    <button type="button" class="btn btn-outline-secondary me-2" onclick="history.back()">
                        {{ __('cancel') }}
                    </button>
                    <button type="submit" form="edit-form" class="btn btn-dark">
                        {{ __('save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
