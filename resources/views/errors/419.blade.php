@extends('errors::layout')

@section('title', __('Halaman Kedaluwarsa'))
@section('message', __('Sesi Anda telah berakhir. Silakan muat ulang halaman dan coba lagi'))
@section('image')
    <img src="{{ asset('app/assets/media/error/419.svg') }}" alt="" class="mw-100 mh-300px theme-light-show"
        width="350" />
@endsection
