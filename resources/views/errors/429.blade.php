@section('title', __('Terlalu Banyak Permintaan'))
@section('message', __('Anda telah melakukan terlalu banyak permintaan. Mohon tunggu beberapa saat dan coba lagi'))
@section('image')
    <img src="{{ asset('app/assets/media/error/429.png') }}" alt="" class="mw-100 mh-300px theme-light-show"
        width="350" />
@endsection
