<div class="fv-row">
    @if ($label)
        <x-form-label class="mb-5" for="{{ $id }}_1" :required="$required">{{ $label }}</x-form-label>
    @endif

    <div class="d-flex align-items-center flex-wrap gap-5">
        @foreach ($options as $key => $value)
            <div class="form-check form-check-custom form-check">
                <input class="form-check-input" type="radio" value="{{ $key }}" id="{{ $id }}_{{ $loop->iteration }}"
                    name="{{ $name }}" @checked($key == old($name, $defaultValue)) @required($required) />
                <label class="form-check-label text-dark"
                    for="{{ $id }}_{{ $loop->iteration }}">{!! $value !!}</label>
            </div>
        @endforeach
    </div>

    @error($errorKey())
        <x-form-error id="{{ $id }}-error">{{ $message }}</x-form-error>
    @enderror

    @if ($help)
        <x-form-help id="{{ $id }}-help">{{ $help }}</x-form-help>
    @endif
</div>
