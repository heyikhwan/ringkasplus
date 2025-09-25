<?php

use Illuminate\Support\Facades\File;

foreach (File::allFiles(__DIR__ . '/web') as $routeFile) require $routeFile->getPathname();
