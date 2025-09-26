<form action="{{ route($permission_name . '.change-status', encode($result->id)) }}" method="post" id="form" class="form w-100"
    onsubmit="submitModalDataTable(this); return false;">
    @method('PUT')
    @csrf

    <div class="row g-5">
        <div class="col-12">
            <x-form-select name="status" label="Status" :options="$statusOptions" :defaultValue="old('status', $result->status)" :disableSearch=true :required=true />
        </div>
    </div>
</form>
