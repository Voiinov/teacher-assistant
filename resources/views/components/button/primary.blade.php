@props([
    'type' => 'button',
    'class-type' => ''
])

<x-button
    {{ $attributes->merge(['class' => 'btn-primary']) }}>
    {{ $slot }}
</x-button>
