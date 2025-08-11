{{--
/* ----------------------------------------------------------------------------
 * Apphold - Online Software Telemetry
 *
 * @package     Apphold
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://github.com/alextselegidis/apphold
 * ---------------------------------------------------------------------------- */
--}}

@php use App\Enums\RoleEnum; @endphp

@extends('layouts.main-layout')

@section('pageTitle')
    {{$user->name}}
@endsection

@section('navTitle')
    {{__('edit_user')}}
@endsection

@section('navActions')
    <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#create-modal">
        <i class="bi bi-plus-square me-2"></i>
        {{__('create')}}
    </a>

    <form action="{{route('users.destroy', $user->id)}}"
          method="POST"
          onsubmit="return confirm('{{__('delete_record_prompt')}}')">
        @csrf
        @method('DELETE')

        <button type="submit" class="nav-link">
            <i class="bi bi-trash me-2"></i>
            {{__('delete')}}
        </button>
    </form>
@endsection

@section('content')

    <div class="d-flex">

        <div class="flex-grow-0 sidebar-width">
            @include('shared.settings-sidebar')
        </div>

        <div class="flex-grow-1">

            <form action="{{route('users.update', ['user' => $user->id])}}" method="POST" style="max-width: 800px;"
                  class="m-auto">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">
                        {{ __('name') }}
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="name" name="name" class="form-control" required
                           value="{{ old('name', $user?->name ?? NULL) }}">
                    @error('name')
                    <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">
                        {{ __('email') }}
                        <span class="text-danger">*</span>
                    </label>
                    <input type="email" id="email" name="email" class="form-control" required
                           value="{{ old('email', $user?->email ?? NULL) }}">
                    @error('email')
                    <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">
                        {{ __('role') }}
                        <span class="text-danger">*</span>
                    </label>
                    <select name="role" id="role" class="form-select" required>
                        @foreach(RoleEnum::values() as $role)
                            <option value="{{$role}}" @if($user?->role === $role) selected @endif>
                                {{__($role)}}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                    <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">
                        {{ __('password') }}
                        <span class="text-danger" {{ $user ? 'hidden' : ''  }}>*</span>
                    </label>
                    <input type="password" id="password" name="password" class="form-control"
                           value="{{ old('password') }}" @required(!$user) autocomplete="new-password">
                    @error('password')
                    <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password-confirmation" class="form-label">
                        {{ __('password_repeat') }}
                        <span class="text-danger" {{ $user ? 'hidden' : ''  }}>*</span>
                    </label>
                    <input type="password" id="password-confirmation" name="password_confirmation" class="form-control"
                           value="{{ old('password_confirmation') }}" @required(!$user)>
                    @error('password_confirmation')
                    <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-flex gap-2 justify-content-end">
                    <button type="button" class="btn btn-outline-primary" onclick="history.back()">
                        {{__('cancel')}}
                    </button>
                    <button type="submit" class="btn btn-primary">
                        {{__('save')}}
                    </button>
                </div>
            </form>


        </div>
    </div>

    @include('modals.create-modal', ['route' => route('users.store')])

@endsection

