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

use App\Http\Controllers\Api\V1\IncidentApiV1Controller;
use App\Http\Controllers\Api\V1\IncidentCommentApiV1Controller;
use App\Http\Controllers\Api\V1\MeApiV1Controller;
use App\Http\Controllers\Api\V1\ObserverApiV1Controller;
use App\Http\Controllers\Api\V1\ProjectApiV1Controller;
use App\Http\Controllers\Api\V1\SettingApiV1Controller;
use App\Http\Controllers\Api\V1\TagApiV1Controller;
use App\Http\Controllers\Api\V1\UserApiV1Controller;
use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Me endpoints
    Route::get('me', [MeApiV1Controller::class, 'show']);
    Route::patch('me', [MeApiV1Controller::class, 'update']);
    Route::get('me/tokens', [MeApiV1Controller::class, 'tokens']);
    Route::post('me/tokens', [MeApiV1Controller::class, 'createToken']);
    Route::delete('me/tokens/{tokenId}', [MeApiV1Controller::class, 'revokeToken']);

    // Orion resource endpoints
    Orion::resource('observers', ObserverApiV1Controller::class);
    Orion::resource('tags', TagApiV1Controller::class);
    Orion::resource('incidents', IncidentApiV1Controller::class);
    Orion::resource('incident-comments', IncidentCommentApiV1Controller::class);
    Orion::resource('projects', ProjectApiV1Controller::class);
    Orion::resource('settings', SettingApiV1Controller::class);
    Orion::resource('users', UserApiV1Controller::class);
});
