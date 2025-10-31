<div class="fv-row">
    @if ($label)
        <x-form-label :for="$id" :required="$required">{{ $label }}</x-form-label>
    @endif

    <div class="form-check form-switch form-check-custom form-check-solid">
        <input class="form-check-input" type="checkbox" id="{{ $id }}" name="{{ $name }}" value="1"
            @checked($checked) {{ $attributes }} />
        @if (!empty($labelOn))
            <label class="form-check-label" for="{{ $id }}">
                {{ $labelOn }}
            </label>
        @endif
    </div>

    @error($errorKey())
        <x-form-error id="{{ $id }}-error">{{ $message }}</x-form-error>
    @enderror

    @if ($help)
        <x-form-help id="{{ $id }}-help">{!! $help !!}</x-form-help>
    @endif
</div>
