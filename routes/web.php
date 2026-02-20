<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncidentsController;
use App\Http\Controllers\ObserversController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\PersonalTokenController;
use App\Http\Controllers\RecoveryController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WelcomeController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\RedirectIfAuthenticated;
use Illuminate\Support\Facades\Route;

// Guest

Route::middleware(RedirectIfAuthenticated::class)->group(function () {
    // WelcomeController

    Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

    // LoginController

    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'perform'])->name('login.perform');

    // RecoveryController

    Route::get('/recovery', [RecoveryController::class, 'index'])->name('recovery');
    Route::post('/recovery', [RecoveryController::class, 'perform'])->name('recovery.perform');
});

// Auth

Route::middleware('auth')->group(function () {
    // LogoutController

    Route::post('/logout', [LogoutController::class, 'perform'])->name('logout.perform');

    // DashboardController

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // AccountController

    Route::get('/account', [AccountController::class, 'index'])->name('account');
    Route::put('/account', [AccountController::class, 'update'])->name('account.update');

    // PersonalTokenController

    Route::post('/account/tokens', [PersonalTokenController::class, 'store'])->name('account.tokens.store');
    Route::delete('/account/tokens/{token}', [PersonalTokenController::class, 'destroy'])->name('account.tokens.destroy');

    // AboutController

    Route::get('/about', [AboutController::class, 'index'])->name('about');

    // TagsController

    Route::resource('tags', TagsController::class)->except(['show'])->names([
        'index' => 'tags',
    ]);

    Route::get('/tags/{tag}/observers', [TagsController::class, 'observers'])->name('tags.observers');

    // ObserversController

    Route::resource('observers', ObserversController::class)->except(['show'])->names([
        'index' => 'observers',
    ]);

    Route::get('/observers/{observer}/toggle', [ObserversController::class, 'toggle'])->name('observers.toggle');

    // IncidentsController

    Route::get('/incidents', [IncidentsController::class, 'index'])->name('incidents');
    Route::get('/incidents/{incident}/edit', [IncidentsController::class, 'edit'])->name('incidents.edit');
    Route::put('/incidents/{incident}', [IncidentsController::class, 'update'])->name('incidents.update');
    Route::delete('/incidents/{incident}', [IncidentsController::class, 'destroy'])->name('incidents.destroy');
    Route::get('/incidents/{incident}/comments', [IncidentsController::class, 'comments'])->name('incidents.comments');
    Route::post('/incidents/{incident}/comments', [IncidentsController::class, 'storeComment'])->name('incidents.comments.store');
    Route::delete('/incidents/{incident}/comments/{comment}', [IncidentsController::class, 'destroyComment'])->name('incidents.comments.destroy');

    // Setup routes (Admin only)

    Route::middleware(AdminMiddleware::class)->prefix('setup')->group(function () {
        // SettingsController (Localization)

        Route::get('/localization', [SettingsController::class, 'index'])->name('setup.localization');
        Route::put('/localization', [SettingsController::class, 'update'])->name('setup.localization.update');

        // UsersController

        Route::resource('users', UsersController::class)->except(['show'])->names([
            'index' => 'setup.users',
            'create' => 'setup.users.create',
            'store' => 'setup.users.store',
            'edit' => 'setup.users.edit',
            'update' => 'setup.users.update',
            'destroy' => 'setup.users.destroy',
        ]);
    });
});
