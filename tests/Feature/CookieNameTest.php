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

namespace Tests\Feature;

use Illuminate\Support\Str;
use Tests\TestCase;

class CookieNameTest extends TestCase
{
    public function test_session_cookie_name_is_scoped_to_the_installation(): void
    {
        $name = config('session.cookie');

        $this->assertStringStartsWith(Str::slug(config('app.name'), '_').'_session_', $name);
        $this->assertStringEndsWith(app_instance_id(), $name);
    }

    public function test_instance_id_is_derived_from_the_app_url_and_key(): void
    {
        $this->assertSame(substr(sha1(config('app.url').config('app.key')), 0, 8), app_instance_id());

        // A cached config does not load the .env file, env() would hash an empty string.
        $this->assertNotSame(substr(sha1(''), 0, 8), app_instance_id());
    }

    public function test_the_shared_xsrf_token_cookie_is_not_set(): void
    {
        $response = $this->get(route('login'));

        $response->assertOk();

        $response->assertCookieMissing('XSRF-TOKEN');
    }
}
