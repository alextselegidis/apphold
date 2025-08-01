{{--
/* ----------------------------------------------------------------------------
 * Apphold - Online Software Telemetry
 *
 * @package     Apphold
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @observer        https://apphold.org
 * ---------------------------------------------------------------------------- */
--}}

@extends('layouts.main-layout')

@section('pageTitle')
    {{$observer->title ?: __('edit_observer')}}
@endsection

@section('navTitle')
    {{__('edit_observer')}}
@endsection

@section('navActions')
    <a href="#" class="nav-link me-lg-2" data-bs-toggle="modal" data-bs-target="#create-modal">
        <i class="bi bi-plus-square me-2"></i>
        {{__('create')}}
    </a>

    <form action="{{route('observers.destroy', $observer->id)}}"
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

            <form action="{{route('observers.update', ['observer' => $observer->id])}}" method="POST"
                  style="max-width: 800px;"
                  class="m-auto">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">
                        {{ __('title') }}
                    </label>
                    <input type="text" id="title" name="title" class="form-control"
                           value="{{ old('title', $observer?->title ?? NULL) }}">
                    @error('title')
                    <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="url" class="form-label">
                        {{ __('url') }}
                        <span class="text-danger">*</span>
                    </label>
                    <input type="url" id="url" name="url" class="form-control" required
                           value="{{ old('url', $observer?->url ?? NULL) }}">
                    @error('url')
                    <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="interval" class="form-label">
                        {{ __('interval') }}
                    </label>
                    <input type="number" id="interval" name="interval" class="form-control" min="5" max="999"
                           value="{{ old('interval', $observer?->interval ?? NULL) }}">
                    @error('interval')
                    <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="emails" class="form-label">
                        {{ __('emails') }}
                    </label>
                    <textarea id="emails" name="emails"
                              class="form-control">{{ old('emails', $observer?->emails ?? NULL) }}</textarea>
                    @error('url')
                    <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="project-id" class="form-label">
                        {{ __('project') }}
                    </label>
                    <select id="project-id" name="project_id" class="form-select">
                        <option value=""
                                @if(empty(old('project-id', $observer?->project_id ?? NULL))) selected @endif></option>
                        @foreach($projectOptions as $projectOption)
                            <option value="{{$projectOption->value}}"
                                    @if($observer?->project_id === $projectOption->value) selected @endif>{{$projectOption->label}}</option>
                        @endforeach
                    </select>
                    @error('project_id')
                    <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">
                        {{ __('notes') }}
                    </label>
                    <textarea type="url" id="notes" name="notes"
                              class="form-control">{{ old('notes', $observer?->notes ?? NULL) }}</textarea>
                    @error('url')
                    <span class="form-text text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="{{ $observer->is_active }}"
                        id="is-active"
                        @if($observer->is_active)
                            checked
                        @endif
                    >
                    <label for="is-active">{{ __('active') }}</label>
                </div>

                <div class="mb-3">
                    <input
                        type="checkbox"
                        name="with_ssl_verification"
                        value="{{ $observer->with_ssl_verification }}"
                        id="with-ssl-verification"
                        @if($observer->with_ssl_verification)
                            checked
                        @endif
                    >
                    <label for="with-ssl-verification">{{ __('ssl_verification') }}</label>
                </div>

                @if($tagOptions->count())

                    <h4>{{__('tags')}}</h4>

                    @foreach ($tagOptions as $tagOption)
                        <div>
                            <input
                                type="checkbox"
                                name="tags[]"
                                value="{{ $tagOption->value }}"
                                id="tag_{{ $tagOption->value }}"
                                @if(in_array($tagOption->value, old('tags', $observer->tags->pluck('id')->toArray())))
                                    checked
                                @endif
                            >
                            <label for="tag_{{ $tagOption->value }}">{{ $tagOption->label }}</label>
                        </div>
                    @endforeach
                @endif


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

@endsection

