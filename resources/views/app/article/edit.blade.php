  <x-app-layout title="Ubah {!! $title !!}">
      <form class="form w-100" action="{{ route($permission_name . '.update', encode($result->id)) }}" method="post" enctype="multipart/form-data">
          @csrf
          @method('PUT')

          @include('app.article._form')
      </form>
  </x-app-layout>
