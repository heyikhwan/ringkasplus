<form action="{{ route($permission_name . '.store') }}" method="post" id="form" class="form w-100" enctype="multipart/form-data"
    onsubmit="submitModalDataTable(this); return false;">
    @csrf

    @include('app.user._form')
</form>
