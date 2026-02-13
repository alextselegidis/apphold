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
    {{ $user->name }}

@endsection

@section('breadcrumbs')
    @include('shared.breadcrumb', ['breadcrumbs' => [
        ['label' => __('setup'), 'url' => route('setup.localization')],
        ['label' => __('users'), 'url' => route('setup.users')],
        ['label' => $user->name]
    ]])

@endsection

@section('navActions')
    <a href="#" class="nav-link me-lg-3" data-bs-toggle="modal" data-bs-target="#create-modal">
        <i class="bi bi-plus-square me-2"></i>
        {{ __('add') }}
    </a>
    <form action="{{ route('setup.users.destroy', $user->id) }}"
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
            @include('shared.setup-sidebar')
        </div>
        <!-- Main Content -->
        <div class="flex-grow-1">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <form action="{{ route('setup.users.update', ['user' => $user->id]) }}" method="POST" id="edit-form">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label text-dark small fw-medium">
                                        <span class="text-danger">*</span> {{ __('name') }}
                                    </label>
                                    <input type="text" id="name" name="name" class="form-control" required
                                           value="{{ old('name', $user?->name ?? null) }}">
                                    @error('name')
                                    <span class="form-text text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label text-dark small fw-medium">
                                        <span class="text-danger">*</span> {{ __('email') }}
                                    </label>
                                    <input type="email" id="email" name="email" class="form-control" required
                                           value="{{ old('email', $user?->email ?? null) }}">
                                    @error('email')
                                    <span class="form-text text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="role" class="form-label text-dark small fw-medium">
                                        <span class="text-danger">*</span> {{ __('role') }}
                                    </label>
                                    <select id="role" name="role" class="form-select" required>
                                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>{{ __('admin') }}</option>
                                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>{{ __('user') }}</option>
                                    </select>
                                    @error('role')
                                    <span class="form-text text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-dark small fw-medium">
                                        {{ __('active') }}
                                    </label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                               @if(old('is_active', $user->is_active)) checked @endif>
                                        <label class="form-check-label" for="is_active">
                                            {{ __('user_is_active') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label text-dark small fw-medium">
                                        {{ __('password') }}
                                    </label>
                                    <input type="password" id="password" name="password" class="form-control"
                                           placeholder="{{ __('leave_blank_to_keep_current') }}">
                                    @error('password')
                                    <span class="form-text text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label text-dark small fw-medium">
                                        {{ __('password_confirmation') }}
                                    </label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                                    @error('password_confirmation')
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
    @include('modals.create-modal', ['route' => route('setup.users.store')])

@endsection
