@props([
    'elements',
    'active' => true,
    'active_class' => 'active',
    'title' => false,
])

<ul id="tabs" class='nav nav-tabs' role="tablist">
    @if($title)
        <li class="pt-2 px-3"><h3 class="card-title">{{ $title }}</h3></li>
    @endif
    @foreach ($elements as $key => $item)
        <li class="nav-item">
            <a class="nav-link {{ $active ? $active_class : '' }}" 
               id="{{ "course-tabs-{$key}-nav" }}" 
               data-toggle="pill" 
               href="#{{ $name = "course-tabs-{$key}-tab" }}" 
               role="tab" 
               aria-controls="{{ $name }}" 
               aria-selected="{{ $active ? 'true' : 'false' }}">
                {{ $item }}
            </a>
        </li>
        @php
            // Після першого елементу встановлюємо всі наступні як неактивні
            $active = false;
        @endphp
    @endforeach
</ul>