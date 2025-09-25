<?php

use Carbon\Carbon;

if (!function_exists('encode')) {
    function encode($plain, $serialize = true)
    {
        return encrypt($plain, $serialize);
    }
}

if (!function_exists('decode')) {
    function decode($cipher, $unserialize = true)
    {
        if ($cipher == '') return false;
        try {
            return decrypt($cipher, $unserialize);
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists('responseSuccess')) {
    function responseSuccess(string $message, array $data = [], $csrf = true, $toJson = true, $code = 200)
    {
        $data['success'] = true;
        $data['message'] = $message;

        if ($toJson) return response()->json($data, $code);
        return $data;
    }
}

if (!function_exists('responseFail')) {
    function responseFail(string $message, array $data = [], $csrf = true, $toJson = true, $code = 500)
    {

        $data['success'] = false;
        $data['message'] = $message;

        if ($toJson) return response()->json($data, $code);
        return $data;
    }
}

if (!function_exists('tanggal')) {
    function tanggal($date, $format = 'd F Y')
    {
        if (empty($date)) return '';
        return Carbon::parse($date)->translatedFormat($format);
    }
}

if (!function_exists('notAjaxAbort')) {
    function notAjaxAbort($code = 404)
    {
        if (!request()->ajax()) abort($code);
        return;
    }
}

if (!function_exists('randomGen')) {
    function randomGen($length)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        return $string;
    }
}

if (!function_exists('randomGen2')) {
    function randomGen2($length)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        return $string;
    }
}

if (!function_exists('randomGenAlpha')) {
    function randomGenAlpha($length)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $string = '';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, $max)];
        }
        return $string;
    }
}

if (!function_exists('calculateReadTime')) {
    function calculateReadTime($content)
    {
        $wordCount = str_word_count(strip_tags($content));
        $averageReadingSpeed = 200;
        $readTimeMinutes = ceil($wordCount / $averageReadingSpeed);

        return max(1, $readTimeMinutes);
    }
}