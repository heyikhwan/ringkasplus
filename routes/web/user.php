<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ApplicationSettingController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PermissionApplicationController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Category
    Route::get('category/datatable', [CategoryController::class, 'datatable'])->name('category.datatable');
    Route::resource('category', CategoryController::class)->except('show');

    // Tag
    Route::get('tag/datatable', [TagController::class, 'datatable'])->name('tag.datatable');
    Route::resource('tag', TagController::class)->except('show');

    // Article
    Route::get('article/datatable', [ArticleController::class, 'datatable'])->name('article.datatable');
    Route::delete('article/remove-image/{id}/{field}', [ArticleController::class, 'removeImage'])->name('article.remove-image');
    Route::post('article/toogle-featured/{id}', [ArticleController::class, 'toogleFeatured'])->name('article.toogle-featured');
    Route::match(['get', 'put'], 'article/change-status/{id}', [ArticleController::class, 'changeStatus'])->name('article.change-status');
    Route::resource('article', ArticleController::class)->except('show');

    // User
    Route::get('user/datatable', [UserController::class, 'datatable'])->name('user.datatable');
    Route::post('user/reset-password/{id}', [UserController::class, 'resetPassword'])->name('user.reset-password');
    Route::resource('user', UserController::class)->except('show');

    // Role & Permission
    Route::get('role-permission/datatable', [RolePermissionController::class, 'datatable'])->name('role-permission.datatable');
    Route::resource('role-permission', RolePermissionController::class)->except('show');

    // Permission Application
    Route::resource('permission-application', PermissionApplicationController::class)->only(['create', 'store']);

    // Application Settings
    Route::match(['get', 'put'], 'application-setting/general', [ApplicationSettingController::class, 'general'])->name('application-setting.general');
    Route::match(['get', 'put'], 'application-setting/social-media', [ApplicationSettingController::class, 'socialMedia'])->name('application-setting.social-media');

    // Account
    Route::post('account/send-verify-email', [AccountController::class, 'sendVerifyEmail'])->name('account.sendVerifyEmail')
        ->middleware('throttle:3,1');
    Route::get('account/verify-email/{id}/{hash}', [AccountController::class, 'verifyEmail'])
        ->middleware('signed')
        ->name('account.verifyEmail');

    Route::match(['get', 'post'], 'account/change-password', [AccountController::class, 'changePassword'])->name('account.change-password');
    Route::put('account/{user}', [AccountController::class, 'update'])->name('account.update');
    Route::resource('account', AccountController::class)->only(['index', 'edit']);

    // Activity Log
    Route::get('activity-log/datatable', [ActivityLogController::class, 'datatable'])->name('activity-log.datatable');
    Route::resource('activity-log', ActivityLogController::class)->only(['index', 'show']);

    // Upload Controller
    Route::post('upload-editor', [UploadController::class, 'uploadEditor'])->name('upload.editor');
});
