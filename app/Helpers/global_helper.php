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
