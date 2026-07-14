<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::view('/components', 'pages.components')->name('pages.components');
    Route::view('/errors-preview', 'pages.errors-preview')->name('pages.errors-preview');
    Route::get('/errors-preview/{code}', function ($code) {
        if (in_array((int)$code, [401, 402, 403, 404, 419, 429, 500, 503])) {
            abort((int)$code);
        }
        abort(404);
    })->name('pages.errors-preview.show');

    // Sample Boilerplate Items CRUD
    Route::prefix('sample-items')->group(function () {
        Route::get('/', [SampleItemController::class, 'index'])->name('pages.sample-items');
        Route::get('/create', [SampleItemController::class, 'create'])->name('pages.sample-items.create');
        Route::post('/', [SampleItemController::class, 'store'])->name('pages.sample-items.store');
        Route::get('/edit/{id}', [SampleItemController::class, 'edit'])->name('pages.sample-items.edit');
        Route::put('/{id}', [SampleItemController::class, 'update'])->name('pages.sample-items.update');
        Route::delete('/{id}', [SampleItemController::class, 'destroy'])->name('pages.sample-items.destroy');
        Route::patch('/{id}/toggle', [SampleItemController::class, 'toggleStatus'])->name('pages.sample-items.toggle');
    });

    // Shared Configuration & Profile Settings
    Route::get('/settings', [Settings\ProfileController::class, 'show'])->name('pages.settings');
    Route::post('/settings/profile', [Settings\ProfileController::class, 'update'])->name('pages.settings.profile');
    Route::get('/settings/password', [Settings\PasswordController::class, 'show'])->name('pages.settings.password.show');
    Route::post('/settings/password', [Settings\PasswordController::class, 'update'])->name('pages.settings.password');
    Route::get('/settings/sessions', [Settings\SessionController::class, 'index'])->name('pages.settings.sessions.show');
    Route::delete('/settings/session/{id}', [Settings\SessionController::class, 'destroy'])->name('pages.settings.session.revoke');
    Route::get('/settings/system', [Settings\SystemController::class, 'show'])->name('pages.settings.system.show');
});
