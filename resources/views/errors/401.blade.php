@extends('errors::layout')

@section('title', __('Akses Ditolak'))
@section('message', __('Anda tidak memiliki izin untuk mengakses halaman ini. Silahkan login terlebih dahulu'))
@section('image')
    <img src="{{ asset('app/assets/media/error/401.svg') }}" alt="" class="mw-100 mh-300px theme-light-show"
        width="350" />
@endsection
