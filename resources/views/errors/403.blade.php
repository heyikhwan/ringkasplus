@extends('errors::layout')

@section('title', __('Akses Ditolak'))
@section('message', __('Anda tidak diizinkan mengakses halaman ini'))
@section('image')
    <img src="{{ asset('app/assets/media/error/403.svg') }}" alt="" class="mw-100 mh-300px theme-light-show"
        width="350" />
@endsection
