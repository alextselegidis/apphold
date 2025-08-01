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
    {{$tag->name}}
@endsection

@section('navTitle')
    {{__('edit_tag')}}
@endsection

@section('navActions')
    <a href="#" class="nav-link me-lg-2" data-bs-toggle="modal" data-bs-target="#create-modal">
        <i class="bi bi-plus-square me-2"></i>
        {{__('create')}}
    </a>

    <form action="{{route('tags.destroy', $tag->id)}}"
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

            <form action="{{route('tags.update', ['tag' => $tag->id])}}" method="POST" style="max-width: 800px;" class="m-auto">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="name" class="form-label">
                        {{ __('name') }}
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="name" name="name" class="form-control" required
                           value="{{ old('name', $tag?->name ?? null) }}">
                    @error('name')
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

    @include('modals.create-modal', ['route' => route('tags.store')])

@endsection

