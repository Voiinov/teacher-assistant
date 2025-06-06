@props([
    'elements',
    'active' => false,
    'active_class' => 'show active',
    'title' => false,
    'key' => false,
    'course'
])

<div id="{{ "course-tabs-{$course}-tab" }}" 
    class="tab-pane fade {{ $active ? $active_class : '' }}" 
    role="tabpanel"
    aria-labelledby="#{{ $name = "course-tabs-{$course}-nav" }}" 
    aria-controls="{{ $name }}">
    {{ $slot }}
</div>