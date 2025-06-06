@aware([
    'xl',
    'lg',
    "color"=>"primary",
])

<div @class([
    'ribbon-wrapper',
    'ribbon-xl' => $xl,
    'ribbon-lg' => $lg,
])>
    <div @class([
        'ribbon',
        "bg-". $color,
        'text-xl' => $xl,
        'text-lg' => $lg,
    ])>
    {{ $slot }}
    </div>
</div>