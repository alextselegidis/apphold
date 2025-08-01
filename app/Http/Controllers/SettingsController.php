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

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        return view('pages.settings', [
            'defaultLocale' => setting('default_locale'),
            'defaultTimezone' => setting('default_timezone'),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'default_locale' => 'required',
            'default_timezone' => 'required',
        ]);

        setting([
            'default_locale' => $request->input('default_locale'),
            'default_timezone' => $request->input('default_timezone'),
        ]);

        return redirect(route('settings'))->with('success', __('recordsSavedMessage'));
    }
}
