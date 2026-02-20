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
    {{__('account')}}

@endsection

@section('breadcrumbs')
    @include('shared.breadcrumb', ['breadcrumbs' => [
        ['label' => __('account')]
    ]])

@endsection

@section('content')
    <div>
        <div style="max-width: 600px" class="mx-auto my-4">
            <!-- Account Details Card -->
            <h5 class="text-dark fw-bold mb-3">{{ __('profile') }}</h5>
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('account.update') }}" method="POST" id="account-form">
                        @csrf
                        @method('PUT')
                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label text-dark fw-medium">
                                <span class="text-danger">*</span> {{ __('name') }}
                            </label>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', auth()->user()->name) }}"
                                required
                            >
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label text-dark fw-medium">
                                <span class="text-danger">*</span> {{ __('email') }}
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', auth()->user()->email) }}"
                                required
                            >
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>
                </div>
                <!-- Card Footer with Save Button -->
                <div class="card-footer bg-body-secondary border-top text-end py-3 px-4">
                    <button type="submit" form="account-form" class="btn btn-dark">
                        {{ __('save') }}
                    </button>
                </div>
            </div>
            <!-- Change Password Section -->
            <h5 class="text-dark fw-bold mb-3">{{ __('password') }}</h5>
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <form action="{{ route('account.update') }}" method="POST" id="password-form">
                        @csrf
                        @method('PUT')
                        <!-- Hidden fields to preserve account data -->
                        <input type="hidden" name="name" value="{{ auth()->user()->name }}">
                        <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label text-dark fw-medium">
                                {{ __('new_password') }}
                            </label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                autocomplete="new-password"
                            >
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Password Confirmation -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label text-dark fw-medium">
                                {{ __('password_repeat') }}
                            </label>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control"
                                autocomplete="new-password"
                            >
                        </div>
                    </form>
                </div>
                <!-- Card Footer with Save Button -->
                <div class="card-footer bg-body-secondary border-top text-end py-3 px-4">
                    <button type="submit" form="password-form" class="btn btn-dark">
                        {{ __('save') }}
                    </button>
                </div>
            </div>

            <!-- Personal Access Tokens Section -->
            <h5 class="text-dark fw-bold mb-3">{{ __('personal_access_tokens') }}</h5>
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    @if(session('new_token'))
                        <div class="alert alert-success">
                            <strong>{{ __('token_created') }}</strong>
                            <p class="mb-0 mt-2">
                                {{ __('token_copy_warning') }}
                            </p>
                            <div class="mt-2">
                                <code class="user-select-all">{{ session('new_token') }}</code>
                            </div>
                        </div>
                    @endif

                    <!-- Create New Token -->
                    <form action="{{ route('account.tokens.store') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="row g-2 align-items-end">
                            <div class="col">
                                <label for="token_name" class="form-label text-dark fw-medium">
                                    {{ __('token_name') }}
                                </label>
                                <input
                                    type="text"
                                    id="token_name"
                                    name="token_name"
                                    class="form-control @error('token_name') is-invalid @enderror"
                                    placeholder="{{ __('token_name_placeholder') }}"
                                    required
                                >
                                @error('token_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn btn-dark">
                                    <i class="bi bi-plus-lg me-1"></i>
                                    {{ __('create_token') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Token List -->
                    @if($tokens->count() > 0)
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>{{ __('name') }}</th>
                                    <th>{{ __('last_used') }}</th>
                                    <th>{{ __('created') }}</th>
                                    <th class="text-end">{{ __('actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tokens as $token)
                                    <tr>
                                        <td>{{ $token->name }}</td>
                                        <td>{{ $token->last_used_at ? $token->last_used_at->diffForHumans() : __('never') }}</td>
                                        <td>{{ $token->created_at->diffForHumans() }}</td>
                                        <td class="text-end">
                                            <form action="{{ route('account.tokens.destroy', $token->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('{{ __('confirm_delete_token') }}')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted mb-0">{{ __('no_tokens') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
