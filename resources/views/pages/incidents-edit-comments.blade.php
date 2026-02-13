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
            <!-- Add Comment Form -->
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body p-4">
                    <h6 class="mb-3">{{ __('add_comment') }}</h6>
                    <form action="{{ route('incidents.comments.store', $incident->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <textarea id="content" name="content" class="form-control" rows="3"
                                      placeholder="{{ __('write_your_comment') }}..." required>{{ old('content') }}</textarea>
                            @error('content')
                            <span class="form-text text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-dark">
                                <i class="bi bi-plus-circle me-1"></i>{{ __('add_comment') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Comments List -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h6 class="mb-3">{{ __('comments') }} ({{ $comments->count() }})</h6>
                    @if($comments->count())
                        @foreach($comments as $comment)
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong>{{ $comment->user->name }}</strong>
                                        <span class="text-muted small ms-2">
                                            {{ $comment->created_at->locale(setting('default_locale', 'en'))->timezone(setting('default_timezone', 'UTC'))->isoFormat('L LT') }}
                                        </span>
                                    </div>
                                    <form action="{{ route('incidents.comments.destroy', [$incident->id, $comment->id]) }}"
                                          method="POST"
                                          onsubmit="return confirm('{{ __('delete_record_prompt') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                                <p class="mb-0">{{ $comment->content }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted mb-0">{{ __('no_comments_yet') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
