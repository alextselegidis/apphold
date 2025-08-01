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
    {{__('settings')}}
@endsection

@section('content')

    <div class="d-flex flex-column-reverse flex-lg-row">

        <div class="flex-grow-0 sidebar-width">
            @include('shared.settings-sidebar')
        </div>

        <div class="flex-grow-1 mb-5 mb-lg-0">

            <form action="{{route('settings.update')}}" method="POST" class="m-auto" style="max-width: 600px">
                @csrf
                @method('PUT')

                {{-- LOCALIZATION --}}

                <h2 class="fs-4">
                    {{__('localization')}}
                </h2>

                <div class="mb-3">
                    <label for="default-locale" class="form-label">
                        {{ __('default_locale') }}
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="default-locale" name="default_locale" class="form-control" required
                           value="{{ old('default_locale', $defaultLocale ?? '') }}">
                    @error('default_locale')
                    <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="default-timezone" class="form-label">
                        {{ __('default_timezone') }}
                        <span class="text-danger">*</span>
                    </label>

                    @include('shared.timezone-dropdown', [
                        'name' => 'default_timezone',
                        'id' => 'default-timezone',
                        'value' => $defaultTimezone,
                        'required'=> true,
                    ])

                    @error('default_timezone')
                        <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        {{__('save')}}
                    </button>
                </div>
            </form>

        </div>

    </div>

@endsection

