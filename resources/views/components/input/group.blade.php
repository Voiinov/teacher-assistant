@aware([
    'error',
])

@props([
    'label',
    'for',
    'help',
    'error',
])

<div
    {{ $attributes }}
    {{ $attributes->class(['form-group']) }}
>
    <label
        for="{{ $for }}"
        @class([
            // 'text-gray-700 inline-block mb-1',
            'text-danger' => $error
        ])
    >{{ $label ?? '' }}</label>
        {{ $slot }}
    @isset($help)
        <p class="text-sm text-gray-500" id="{{ $for }}">{{ $help }}</p>
    @endif
    @isset($error)
        <span class="error invalid-feedback">{{ $error }}</span>
    @endif
</div>
