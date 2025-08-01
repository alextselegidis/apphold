<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ObserversController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\RecoveryController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WelcomeController;
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

    // AboutController

    Route::get('/about', [AboutController::class, 'index'])->name('about');

    // SettingsController

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');

    // UsersController

    Route::resource('users', UsersController::class)->names([
        'index' => 'users',
    ]);

    // TagsController

    Route::resource('tags', TagsController::class)->names([
        'index' => 'tags',
    ]);

    // ProjectsController

    Route::resource('projects', ProjectsController::class)->names([
        'index' => 'projects',
    ]);

    // ObserversController

    Route::resource('observers', ObserversController::class)->names([
        'index' => 'observers',
    ]);

    Route::get('/observers/{observer}/toggle', [ObserversController::class, 'toggle'])->name('observers.toggle');
});
