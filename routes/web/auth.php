<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    // login
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login');

    // forgot password
    Route::get('forgot-password', [ForgotPasswordController::class, 'index'])->name('auth.forgot-password');
    Route::post('forgot-password', [ForgotPasswordController::class, 'store'])->name('auth.forgot-password');

    // reset password
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'index'])->name('auth.reset-password.index')
        ->middleware('signed');
    Route::post('reset-password', [ResetPasswordController::class, 'store'])->name('auth.reset-password.store');
});

Route::middleware(['auth'])->group(function () {
    Route::post('logout', LogoutController::class)->name('auth.logout'); //invoke
});
