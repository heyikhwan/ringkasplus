<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // User
    Route::get('user/datatable', [UserController::class, 'datatable'])->name('user.datatable');
    Route::post('user/reset-password/{id}', [UserController::class, 'resetPassword'])->name('user.reset-password');
    Route::resource('user', UserController::class)->except('show');

    // Role & Permission
    Route::get('role-permission/datatable', [RolePermissionController::class, 'datatable'])->name('role-permission.datatable');
    Route::resource('role-permission', RolePermissionController::class)->except('show');
});
