@aware([
    'type'=>'button',
    'size'=>'',
    ])

@props([
    'class-type' => 'btn-default'
])

<button
    type="{{ $type }}"
    {{ $attributes->class([
        'btn'
    ]) }}
>
    {{ $slot }}
</button>
