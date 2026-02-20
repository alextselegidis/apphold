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

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MeApiV1Controller extends Controller
{
    /**
     * Get the authenticated user's profile.
     */
    public function show(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $request->user(),
        ]);
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:8|confirmed',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'data' => $user->fresh(),
        ]);
    }

    /**
     * Get the authenticated user's personal access tokens.
     */
    public function tokens(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $request->user()->tokens,
        ]);
    }

    /**
     * Create a new personal access token.
     */
    public function createToken(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $token = $request->user()->createToken($validated['name']);

        return response()->json([
            'data' => [
                'token' => $token->accessToken,
                'plain_text_token' => $token->plainTextToken,
            ],
        ], 201);
    }

    /**
     * Revoke a personal access token.
     */
    public function revokeToken(Request $request, int $tokenId): JsonResponse
    {
        $request->user()->tokens()->where('id', $tokenId)->delete();

        return response()->json([
            'message' => 'Token revoked successfully.',
        ]);
    }
}
