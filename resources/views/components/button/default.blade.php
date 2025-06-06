@props([
    'type' => 'button',
    'class-type' => ''
])

<x-button
    {{ $attributes->merge(['class' => 'btn-default']) }}>
    {{ $slot }}
</x-button>
