<div class="fv-row">
    @if ($label)
        <x-form-label :for="$id" :required="$required">{{ $label }}</x-form-label>
    @endif

    <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}"
        {{ !empty($cols) ? 'cols="' . $cols . '"' : '' }} class="form-control @error($errorKey()) is-invalid @enderror"
        placeholder="{{ $placeholder }}" @if ($required) required @endif style="resize: none"
        {{ $attributes }}>{{ old($errorKey(), $value) }}</textarea>

    @error($errorKey())
        <x-form-error id="{{ $id }}-error">{{ $message }}</x-form-error>
    @enderror

    @if ($help)
        <x-form-help id="{{ $id }}-help">{!! $help !!}</x-form-help>
    @endif
</div>
