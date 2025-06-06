@props([
    'id' => 'modal-default',
    'size'=>null,
])

<div {{ $attributes->class([
    "modal",
    "fade"
 ]) }} id="{{ $id }}">
    <div class="modal-dialog @if($size!=null) modal-{{ $size }}  @endif" role="document">
        <div class="modal-content">
            {{ $slot }}
        </div>        
    </div>
</div>
