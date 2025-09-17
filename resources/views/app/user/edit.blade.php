    <form action="{{ route($permission_name . '.update', encode($result->id)) }}" method="post" id="form"
        class="form w-100" enctype="multipart/form-data" onsubmit="submitModalDataTable(this); return false;">
        @csrf
        @method('PUT')

        @include('app.user._form')
    </form>
