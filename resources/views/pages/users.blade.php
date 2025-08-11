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

@extends('layouts.main-layout')

@section('pageTitle')
    {{__('Users')}}
@endsection

@section('navActions')
    <a href="#" class="nav-link" data-bs-toggle="modal" data-bs-target="#create-modal">
        <i class="bi bi-plus-square me-2"></i>
        {{__('create')}}
    </a>
@endsection

@section('content')

    <div class="d-flex flex-column-reverse flex-lg-row">

        <div class="flex-grow-0 sidebar-width">
            @include('shared.settings-sidebar')
        </div>

        <div class="flex-grow-1 mb-5 mb-lg-0">

            <form action="{{route('users')}}" method="GET" class="mb-3">
                @csrf
                @method('GET')
                <div class="input-group mb-3">
                    <span class="bg-body-tertiary input-group-text px-3">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="q" name="q" class="form-control bg-body-tertiary border-start-0"
                           value="{{$q}}"
                           placeholder="{{__('search')}}" autofocus tabindex="-1" style="max-width: 300px;">
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th style="width: 10%">
                            <a href="{{ route('users', ['sort' => 'id', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                                {!! sort_link('id', __('id')) !!}
                            </a>
                        </th>
                        <th style="width: 30%">
                            <a href="{{ route('users', ['sort' => 'name', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                                {!! sort_link('name', __('name')) !!}
                            </a>
                        </th>
                        <th style="width: 30%">
                            <a href="{{ route('users', ['sort' => 'email', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                                {!! sort_link('email', __('email')) !!}
                            </a>
                        </th>
                        <th style="width: 30%">
                            <a href="{{ route('users', ['sort' => 'role', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                                {!! sort_link('role', __('role')) !!}
                            </a>
                        </th>
                        <th style="width: 15%">
                            <a href="{{ route('users', ['sort' => 'is_active', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">
                                {!! sort_link('is_active', __('active')) !!}
                            </a>
                        </th>
                        <th style="width: 15%">
                            <!-- Actions -->
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr onclick="window.location='{{route('users.show', $user->id)}}'" style="cursor: pointer;">
                            <td>
                                @include('shared.id-value', ['value' => $user->id])
                            </td>
                            <td>
                                {{$user->name}}
                            </td>
                            <td>
                                @include('shared.email-value', ['value' => $user->email])
                            </td>
                            <td>
                                {{__($user->role)}}
                            </td>
                            <td>
                                {{__($user->is_active ? 'yes' : 'no')}}
                            </td>
                            <td class="text-end">
                                <div class="dropdown" onclick="event.stopPropagation();">
                                    <button class="btn btn-link text-decoration-none dropdown-toggle py-0" type="button"
                                            data-bs-toggle="dropdown">
                                        {{__('actions')}}
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a href="{{route('users.edit', ['user' => $user->id])}}"
                                               class="dropdown-item">
                                                {{__('edit')}}
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{route('users.destroy', $user->id)}}"
                                                  method="POST"
                                                  onsubmit="return confirm('{{__('delete_record_prompt')}}')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item">
                                                    {{__('delete')}}
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>

                            </td>
                        </tr>
                    @endforeach

                    @if($users->isEmpty())
                        @include('shared.no_records_found')
                    @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    @include('modals.create-modal', ['route' => route('users.store')])

@endsection

