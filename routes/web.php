<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

foreach (File::allFiles(__DIR__ . '/web') as $routeFile) require $routeFile->getPathname();