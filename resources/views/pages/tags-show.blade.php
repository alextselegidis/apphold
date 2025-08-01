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
    {{__('view_tag')}}
@endsection

@section('navActions')
    <a href="#" class="nav-link me-lg-3" data-bs-toggle="modal" data-bs-target="#create-modal">
        <i class="bi bi-plus-square me-2"></i>
        {{__('create')}}
    </a>

    <a href="{{ route('tags.edit', ['tag' => $tag->id]) }}" class="nav-link me-lg-3">
        <i class="bi bi-pencil me-2"></i>
        {{__('edit')}}
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

    <div class="d-flex flex-column-reverse flex-lg-row">

        <div class="flex-grow-0 sidebar-width">
            @include('shared.settings-sidebar')
        </div>

        <div class="flex-grow-1">

            @include('shared.show-title', ['title' => $tag->name])

            <div class="d-lg-flex">
                <div class="w-100">
                    @include('shared.show-id', ['label' => __('id'), 'value' => $tag->id])
                    @include('shared.show-text', ['label' => __('count'), 'value' => $tag->count])
                </div>
                <div class="w-100">
                    @include('shared.show-date', ['label' => __('created'), 'value' => $tag->created_at])
                    @include('shared.show-date', ['label' => __('updated'), 'value' => $tag->updated_at])
                </div>
            </div>

        </div>

    </div>

    @include('modals.create-modal', ['route' => route('tags.store')])

@endsection

