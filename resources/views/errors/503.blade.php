@section('title', __('Layanan Tidak Tersedia'))
@section('message', __('Layanan sedang tidak tersedia. Mohon coba lagi beberapa saat'))
@section('image')
    <img src="{{ asset('app/assets/media/error/503-light.svg') }}" alt="" class="mw-100 mh-300px theme-light-show"
        width="350" />
@endsection
