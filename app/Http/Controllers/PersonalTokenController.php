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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonalTokenController extends Controller
{
    public function index()
    {
        $tokens = Auth::user()->tokens;

        return view('pages.account', compact('tokens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'token_name' => 'required|string|max:255',
        ]);

        $token = Auth::user()->createToken($request->token_name);

        return redirect()->route('account')->with([
            'success' => __('token_created'),
            'new_token' => $token->plainTextToken,
        ]);
    }

    public function destroy($tokenId)
    {
        Auth::user()->tokens()->where('id', $tokenId)->delete();

        return redirect()->route('account')->with('success', __('token_deleted'));
    }
}
