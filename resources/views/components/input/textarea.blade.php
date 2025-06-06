@aware([
    'error',
])

@props([
    'text',
    'name',
    'for',
])

<textarea
    {{ $attributes->class([
        'form-control',
        'is-invalid' => $error
    ]) }}
    @isset($name) name="{{ $name }}" @endif
    type="text"
    
    {{ $attributes }}
/>
{{ $slot }}
</textarea>