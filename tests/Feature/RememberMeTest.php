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

use App\Models\User;
use Illuminate\Cookie\CookieValuePrefix;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RememberMeTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_with_remember_issues_a_recaller_cookie(): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret')]);

        $response = $this->post(route('login.perform'), [
            'email' => $user->email,
            'password' => 'secret',
            'remember' => 'on',
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertNotNull($response->getCookie(Auth::guard('web')->getRecallerName()));
    }

    public function test_login_without_remember_does_not_issue_a_recaller_cookie(): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret')]);

        $response = $this->post(route('login.perform'), [
            'email' => $user->email,
            'password' => 'secret',
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertNull($response->getCookie(Auth::guard('web')->getRecallerName()));
    }

    public function test_recaller_cookie_logs_the_user_back_in_after_the_session_is_gone(): void
    {
        $user = User::factory()->create(['password' => Hash::make('secret')]);

        $recaller = Auth::guard('web')->getRecallerName();

        $login = $this->post(route('login.perform'), [
            'email' => $user->email,
            'password' => 'secret',
            'remember' => 'on',
        ]);

        $value = CookieValuePrefix::remove(
            $this->app['encrypter']->decrypt($login->getCookie($recaller, false)->getValue(), false),
        );

        $this->flushSession();
        $this->app['auth']->forgetGuards();

        $this->withCookie($recaller, $value)->get(route('dashboard'))->assertOk();

        $this->assertAuthenticatedAs($user);
    }

    public function test_recaller_cookie_name_is_scoped_to_the_installation(): void
    {
        $name = Auth::guard('web')->getRecallerName();

        $this->assertStringEndsWith(app_instance_id(), $name);
        $this->assertNotSame('remember_web_'.sha1(\Illuminate\Auth\SessionGuard::class), $name);
    }
}
