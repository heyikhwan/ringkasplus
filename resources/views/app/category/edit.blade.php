    <form action="{{ route($permission_name . '.update', encode($result->id)) }}" method="post" id="form" class="form w-100"
        onsubmit="submitModalDataTable(this); return false;">
        @csrf
        @method('PUT')

        @include('app.category._form')
    </form>
