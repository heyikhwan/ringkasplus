<select id="{{ $id }}" class="form-select @error($name) is-invalid @enderror {{ $class }}"
    name="{{ $name }}" {{ $required ? 'required' : '' }} disable-search="{{ $disableSearch }}"
    allow-clear="{{ $allowClear }}" tags="{{ $tags }}" close-on-select="{{ $closeOnSelect }}"
    placeholder="{{ $placeholder }}" {{ $attributes }}>
    <option value="">{{ $placeholder }}</option>
    @foreach ($options as $key => $value)
        <option value="{{ $key }}" @selected($defaultValue == $key)>{{ $value }}</option>
    @endforeach
</select>

@if (empty($noScript))
    <script>
        $(document).ready(function() {
            loadSelect2("#{{ $id }}");
        })
    </script>
@endif
