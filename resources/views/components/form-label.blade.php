<label for="{{ $for }}" {{ $attributes->merge(['class' => 'form-label' . ($required ? ' required' : '')]) }}>
    {{ $slot }}
</label>
