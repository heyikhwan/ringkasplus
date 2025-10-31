<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('checkFile')) {
    function checkFile($path)
    {
        return $path ? Storage::exists($path) : false;
    }
}

if (!function_exists('getFileUrl')) {
    function getFileUrl($path, $default = null, $disk = 'public')
    {
        if (empty($path)) {
            return $default;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        return Storage::disk($disk)->url($path);
    }
}
