@props([
    'id' => 'modal-default',
    'size' => NULL,
])

<x-modal.index 
    id="{{ $id }}" 
    size="{{ $size }}"
>
 {{ $slot }}
</x-modal.index>