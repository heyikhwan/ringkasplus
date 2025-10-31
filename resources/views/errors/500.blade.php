@section('title', __('Kesalahan Server'))
@section('message', __('Terjadi kesalahan di server kami. Mohon coba lagi nanti'))
@section('image')
    <img src="{{ asset('app/assets/media/error/500-light.svg') }}" alt="" class="mw-100 mh-300px theme-light-show"
        width="350" />
@endsection
