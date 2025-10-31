@extends('errors::layout')

@section('title', __('Pembayaran Dibutuhkan'))
@section('message', __('Akses ke halaman ini memerlukan pembayaran. Mohon selesaikan pembayaran Anda'))
@section('image')
    <img src="{{ asset('app/assets/media/error/402.png') }}" alt="" class="mw-100 mh-300px theme-light-show"
        width="350" />
@endsection
