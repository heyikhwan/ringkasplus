<?php

use Illuminate\Support\Facades\Route;

foreach (File::allFiles(__DIR__ . '/web') as $routeFile) require $routeFile->getPathname();