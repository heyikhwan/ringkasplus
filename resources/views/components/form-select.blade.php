<div class="fv-row">
    @if (!empty($label))
        <x-form-label :for="$id" :required="$required">{{ $label }}</x-form-label>
    @endif

    <div class="position-relative d-flex align-items-center">
        @isset($prepend)
            {{ $prepend }}
        @endisset

        <select id="{{ $id }}" class="form-select @error($name) is-invalid @enderror {{ $class }}"
            name="{{ $name }}" {{ $required ? 'required' : '' }} disable-search="{{ $disableSearch }}"
            allow-clear="{{ $allowClear }}" tags="{{ $tags }}" close-on-select="{{ $closeOnSelect }}"
            placeholder="{{ $placeholder }}" {{ $attributes }}>
            @if (!empty($placeholder))
                <option value="">{{ $placeholder }}</option>
            @endif
            @foreach ($options as $key => $value)
                <option value="{{ $key }}" @selected($defaultValue == $key)>{{ $value }}</option>
            @endforeach
        </select>
    </div>
    @error($name)
        <x-form-error>{{ $message }}</x-form-error>
    @enderror

    @if (!empty($help))
        <x-form-help>{{ $help }}</x-form-help>
    @endif
</div>

@if (empty($noScript))
    @push('scripts')
        <script>
            $(document).ready(function() {
                loadSelect2("#{{ $id }}");
            })
        </script>
    @endpush
@endif
