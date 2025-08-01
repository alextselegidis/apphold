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
    {{$observer->title ?: __('view_observer')}}
@endsection

@section('navTitle')
    {{__('view_observer')}}
@endsection

@section('navActions')
    <a href="#" class="nav-link me-lg-3" data-bs-toggle="modal" data-bs-target="#create-modal">
        <i class="bi bi-plus-square me-2"></i>
        {{__('create')}}
    </a>

    <a href="{{ route('observers.edit', ['observer' => $observer->id]) }}" class="nav-link me-lg-3">
        <i class="bi bi-pencil me-2"></i>
        {{__('edit')}}
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

    <div class="d-flex flex-column-reverse flex-lg-row">

        <div class="flex-grow-0 sidebar-width">
            @include('shared.settings-sidebar')
        </div>

        <div class="flex-grow-1">

            @include('shared.show-title', ['title' => $observer->title, 'icon' => $observer->favicon])

            <div class="d-lg-flex">
                <div class="w-100">
                    @include('shared.show-id', ['label' => __('id'), 'value' => $observer->id])
                    @include('shared.show-link', ['label' => __('url'), 'href' => $observer->url, 'value' => $observer->formatted_url])
                    @include('shared.show-text', ['label' => __('interval'), 'value' => $observer->interval])
                    @include('shared.show-text', ['label' => __('project'), 'value' => $observer->project?->name])
                    @include('shared.show-text', ['label' => __('emails'), 'value' => $observer->emails])
                    @include('shared.show-text', ['label' => __('tags'), 'value' => $observer->formatted_tags])
                    @include('shared.show-bool', ['label' => __('active'), 'value' => $observer->is_active])
                    @include('shared.show-bool', ['label' => __('ssl_verification'), 'value' => $observer->with_ssl_verification])
                    @include('shared.show-text', ['label' => __('notes'), 'value' => $observer->notes])
                </div>
                <div class="w-100">
                    @include('shared.show-date', ['label' => __('created'), 'value' => $observer->created_at])
                    @include('shared.show-date', ['label' => __('updated'), 'value' => $observer->updated_at])
                </div>
            </div>

        </div>
    </div>

    @include('modals.create-modal', ['route' => route('observers.store'), 'input_name' => 'url', 'input_type' => 'url'])

@endsection

