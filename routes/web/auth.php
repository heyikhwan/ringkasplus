<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->name('auth.')->group(function () {
    Route::get('login', [LoginController::class, 'index'])->name('login');
});