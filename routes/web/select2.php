<?php

use App\Http\Controllers\Select2Controller;
use Illuminate\Support\Facades\Route;

Route::prefix('select2')->name('select2.')->controller(Select2Controller::class)->group(function () {
    Route::get('roles', 'roles')->name('roles');
    Route::get('categories', 'categories')->name('categories');
    Route::get('tags', 'tags')->name('tags');
});
