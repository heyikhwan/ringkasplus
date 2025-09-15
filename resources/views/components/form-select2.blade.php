<div class="fv-row">
    @if (!empty($label))
        <x-form-label :for="$id" :required="$required">{{ $label }}</x-form-label>
    @endif

    <select id="{{ $id }}" name="{{ $name }}"
        class="form-select select2 {{ $class }} @error($name) is-invalid @enderror"
        data-options='@json($options)' {{ $required ? 'required' : '' }} {{ $disabled ? 'disabled' : '' }}
        {{ $attributes }}>
        {{ $slot }}
    </select>

    @error($name)
        <x-form-error>{{ $message }}</x-form-error>
    @enderror

    @if (!empty($help))
        <x-form-help>{{ $help }}</x-form-help>
    @endif
</div>

@if (!$noScript)
    <script>
        $(function() {
            const el = $('#{{ $id }}');
            const options = el.data('options') || {};
            loadSelect2PerPage(el, options);
        });
    </script>
@endif
