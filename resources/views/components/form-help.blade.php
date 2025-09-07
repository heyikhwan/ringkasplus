@php
    $attributes = $attributes->class(['form-text']);
@endphp

<div {{ $attributes }}>
    {{ $slot }}
</div>
