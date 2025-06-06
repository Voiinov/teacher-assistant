@aware([
    'error',
])

@props([
    'value',
    'name',
    'items',
    'for',
    "addEmpty",
    'selected'=>"",
])

<select
    {{ $attributes->class([
        'form-control',
        'is-invalid' => $error
    ]) }}
    @isset($name) name="{{ $name }}" @endif
    @isset($value) value="{{ $value }}" @endif
    {{ $attributes }}
/>
@isset($addEmpty)
<option value="{{ $addEmpty }}" selected></option>
@endisset
@isset($items)
    @foreach ($items as $item)
            <option value="{{ $item->id }}" @if($selected==$item->id) selected @endif>{{ $item->title }}</option>
    @endforeach
@endisset
</select>
