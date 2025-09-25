<x-app-layout title="Tambah {!! $title !!}">
    <form class="form w-100" action="{{ route($permission_name . '.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        @include('app.article._form')
    </form>
</x-app-layout>
