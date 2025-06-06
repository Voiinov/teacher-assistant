@props([
    'breadcrumbs',
])

<div class="row mb-2">
    <div class="col-sm-6">
        <h1> {{ $slot }}</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            @isset($breadcrumbs)
                <li class="breadcrumb-item"><a href="{{ url("/home") }}">{{ __("Home") }}</a></li>
                @if (count($breadcrumbs) > 1)
                    @foreach ($breadcrumbs as $item)
                        @if (isset($item['current']))
                            <li class="breadcrumb-item active">{{ __($item['name']) }}</li>
                        @else
                            <li class="breadcrumb-item">
                                @if (isset($item['url'])) 
                                    <a href="{{ $item['url'] }}">{{ __($item['name']) }}</a>
                                @else
                                    {{ __($item['name']) }}
                                @endif
                            </li>
                        @endif
                    @endforeach    
                @else
                    <li class="breadcrumb-item active">{{ ($breadcrumbs[0]['name']) }}</li>
                @endif
            @endif
        </ol>
    </div>
</div>

