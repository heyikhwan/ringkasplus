@extends('errors::layout')

@section('title', __('Halaman Tidak Ditemukan'))
@section('message', __('Halaman yang Anda cari tidak tersedia'))
@section('image')
    <img src="{{ asset('app/assets/media/error/404-light.svg') }}" alt="" class="mw-100 mh-300px theme-light-show"
        width="350" />
@endsection
