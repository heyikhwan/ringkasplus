<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ArticleCategoryController;
use App\Http\Controllers\ArticleTagController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionApplicationController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Article Category
    Route::get('article-category/datatable', [ArticleCategoryController::class, 'datatable'])->name('article-category.datatable');
    Route::resource('article-category', ArticleCategoryController::class)->except('show');

    // Article Tag
    Route::get('article-tag/datatable', [ArticleTagController::class, 'datatable'])->name('article-tag.datatable');
    Route::resource('article-tag', ArticleTagController::class)->except('show');

    // User
    Route::get('user/datatable', [UserController::class, 'datatable'])->name('user.datatable');
    Route::post('user/reset-password/{id}', [UserController::class, 'resetPassword'])->name('user.reset-password');
    Route::resource('user', UserController::class)->except('show');

    // Role & Permission
    Route::get('role-permission/datatable', [RolePermissionController::class, 'datatable'])->name('role-permission.datatable');
    Route::resource('role-permission', RolePermissionController::class)->except('show');

    // Permission Application
    Route::resource('permission-application', PermissionApplicationController::class)->only(['create', 'store']);

    // Account
    Route::post('account/send-verify-email', [AccountController::class, 'sendVerifyEmail'])->name('account.sendVerifyEmail')
        ->middleware('throttle:3,1');
    Route::get('account/verify-email/{id}/{hash}', [AccountController::class, 'verifyEmail'])
        ->middleware('signed')
        ->name('account.verifyEmail');

    Route::match(['get', 'post'], 'account/change-password', [AccountController::class, 'changePassword'])->name('account.change-password');
    Route::put('account/{user}', [AccountController::class, 'update'])->name('account.update');
    Route::resource('account', AccountController::class)->only(['index', 'edit']);
});
