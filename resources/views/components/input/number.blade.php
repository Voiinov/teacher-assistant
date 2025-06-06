@aware([
    'error',
])

@props([
    'value',
    'name',
    'for',
])

<input
    {{ $attributes->class([
        'form-control',
        'is-invalid' => $error
    ]) }}
    @isset($name) name="{{ $name }}" @endif
    type="number"
    @isset($value) value="{{ $value }}" @endif
    {{ $attributes }}
/>
