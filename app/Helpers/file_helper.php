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
        return $path ? Storage::disk($disk)->url($path) : $default;
    }
}