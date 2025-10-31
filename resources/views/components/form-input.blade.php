<div class="fv-row" {{ $type == 'password' ? 'data-kt-password-meter=true' : '' }}>
    @if ($label)
        <x-form-label :for="$id" :required="$required">{{ $label }}</x-form-label>
    @endif

    <div class="position-relative d-flex align-items-center">
        @isset($prepend)
            {{ $prepend }}
        @endisset

        <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}"
            class="form-control @error($errorKey()) is-invalid @enderror {{ $class }}"
            value="{{ $type !== 'password' ? old($errorKey(), $value ?? '') : '' }}" autocomplete="{{ $autocomplete }}"
            placeholder="{{ $placeholder }}" @if ($required) required @endif
            aria-describedby="{{ $id }}-help {{ $id }}-error" {{ $attributes }} />

        @isset($append)
            {{ $append }}
        @endisset
    </div>

    @error($errorKey())
        <x-form-error id="{{ $id }}-error">{{ $message }}</x-form-error>
    @enderror

    @if ($help)
        <x-form-help id="{{ $id }}-help">{!! $help !!}</x-form-help>
    @endif
</div>
