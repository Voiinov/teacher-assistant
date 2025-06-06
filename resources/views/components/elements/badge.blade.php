@aware([
    'success'=>false,
    'danger'=>false,
    'warning'=>false,
    ])

<span {{ $attributes->class([
    'badge',
    'badge-danger'=>$danger,
    'badge-warning'=>$warning,
    'badge-success'=>$success,
]) }}>
    {{ $slot }}
</span>