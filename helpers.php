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

use App\Models\Setting;

if (!function_exists('sort_link')) {
    /**
     * Sortable table header link.
     *
     * The caret comes from the stylesheet, `.table > thead th a` draws it on hover and
     * the "active" and "asc" classes tell it which column is sorted and in which way.
     */
    function sort_link(string $column, string $label, ?string $defaultColumn = null): string
    {
        $active = request('sort', $defaultColumn) === $column;
        $ascending = $active && request('direction', 'desc') === 'asc';
        $url = request()->fullUrlWithQuery(['sort' => $column, 'direction' => $ascending ? 'desc' : 'asc']);
        $class = trim('table-sort ' . ($active ? 'active' : '') . ($ascending ? ' asc' : ''));

        return '<a class="' . $class . '" href="' . e($url) . '">' . e($label) . '</a>';
    }
}

if (!function_exists('setting')) {
    function setting(array|string|null $key = null, mixed $default = null): mixed
    {
        if (empty($key)) {
            throw new InvalidArgumentException('The $key argument cannot be empty.');
        }

        if (is_array($key)) {
            foreach ($key as $name => $value) {
                $setting = Setting::query()->where('name', $name)->first();

                if (empty($setting)) {
                    $setting = new Setting([
                        'name' => $name,
                    ]);
                }

                $setting->value = $value;

                $setting->save();
            }

            return null;
        }

        $setting = Setting::query()->where('name', $key)->first() ?? null;

        return $setting->value ?? $default;
    }
}

if (!function_exists('app_instance_id')) {
    /**
     * Short hash that is unique per Apphold installation.
     *
     * Used to suffix cookie names so that two installations sharing a domain cannot
     * overwrite each other's session or "remember me" cookies. The app key is part of
     * the hash because APP_URL is not always filled in, while the key always is.
     *
     * Reads the config and not env(), a cached config skips loading the .env file and
     * env() would then return null on every installation, giving them all the same id.
     */
    function app_instance_id(): string
    {
        $url = config('app.url') ?? env('APP_URL', '');
        $key = config('app.key') ?? env('APP_KEY', '');

        return substr(sha1($url . $key), 0, 8);
    }
}
