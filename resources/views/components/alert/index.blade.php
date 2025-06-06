@props([
    'class' => 'default',
    'type' => 'default',
])


<div class="alert alert-{{$class}} alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    @switch($type)
        @case("success")
            <h5><i class="icon fas fa-check"></i> {{ __("Success") }}!</h5>
            @break
        @case("errors")
            <h5><i class="icon fas fa-ban"></i> {{ __("Error") }}</h5>
            @break
     
        @default
            
    @endswitch
    {{ $slot }}
</div>