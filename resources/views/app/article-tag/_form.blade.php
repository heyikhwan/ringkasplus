<div class="row g-5">
    <div class="col-md-6">
        <x-form-input label="Nama Tag" name="name" :value="$result->name ?? ''" :required=true />
    </div>

    <div class="col-md-6">
        <x-form-switch label="Status" name="status" labelOn="Aktif" :checked="old('status', $result->status ?? true)" />
    </div>
</div>
