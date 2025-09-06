<x-app-layout title="Tambah {!! $title !!}">
    <form class="form w-100" action="{{ route($permission_name . '.store') }}" method="post">
        @csrf

        @include('app.role-permission._form')
    </form>
</x-app-layout>
