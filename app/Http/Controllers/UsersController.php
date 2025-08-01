<?php

/* ----------------------------------------------------------------------------
 * Apphold - Online Software Telemetry
 *
 * @package     Apphold
 * @author      A.Tselegidis <alextselegidis@gmail.com>
 * @copyright   Copyright (c) Alex Tselegidis
 * @license     https://opensource.org/licenses/GPL-3.0 - GPLv3
 * @link        https://apphold.org
 * ---------------------------------------------------------------------------- */

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        $q = $request->query('q');

        if ($q) {
            $query->where('name', 'like', '%' . $q . '%');
        }

        $sort = $request->query('sort');
        $direction = $request->query('direction');

        if ($sort && $direction) {
            $query->orderBy($sort, $direction);
        }

        $users = $query->cursorPaginate(25);

        return view('pages.users', [
            'users' => $users,
            'q' => $q,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $payload = $request->all();

        $user = User::create([
            'name' => $payload['name'],
            'email' => 'new-user-' . strtolower(Str::random(5)) . '@example.org',
            'password' => Hash::make(Str::random(8)),
        ]);

        return redirect(route('users.edit', ['user' => $user->id]));
        // return redirect(request()->fullUrlWithoutQuery('create'));
    }

    public function show(Request $request, User $user)
    {
        return view('pages.users-show', [
            'user' => $user,
        ]);
    }

    public function edit(Request $request, User $user)
    {
        return view('pages.users-edit', [
            'user' => $user,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|min:2',
            'email' => 'required|min:2',
            'password' => 'nullable|min:8|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'nullable|min:8',
        ]);

        $payload = $request->input();

        if (empty($payload['password'])) {
            unset($payload['password'], $payload['password_confirmation']);
        }

        $user->fill($payload);

        $user->save();

        return redirect(route('users.show', $user->id))->with('success', __('record_saved_message'));
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === request()->user()->id) {
            return redirect()->route('users')->with('error', __('cannotDeleteCurrentUser'));
        }

        $user->delete();

        return redirect()->back()->with('success', __('record_deleted_message'));
    }
}
