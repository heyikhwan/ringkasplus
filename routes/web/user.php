<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // User
    // TODO: Permissionnya belum diatur
    Route::prefix('user')->name('user.')->controller(UserController::class)->group(function () {
        Route::post('reset-password/{id}', 'resetPassword')->name('reset-password');

        Route::get('/', 'index')->name('index');
        Route::get('datatable', 'datatable')->name('datatable');
        Route::get('create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('{id}/edit', 'edit')->name('edit');
        Route::put('{id}', 'update')->name('update');
        Route::delete('{id}', 'destroy')->name('destroy');
    });
});
