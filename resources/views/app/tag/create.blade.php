<form action="{{ route($permission_name . '.store') }}" method="post" id="form" class="form w-100"
    onsubmit="submitModalDataTable(this); return false;">
    @csrf

    @include('app.tag._form')
</form>
