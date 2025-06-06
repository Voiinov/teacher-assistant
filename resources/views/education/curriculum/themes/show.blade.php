@extends('layouts.dashboard')
@section("pageTitle")
    {{ __($pageTitle) . " | " }}
@endsection
@section("CSS")

@endsection
@section('content-header')
    <x-page.header :breadcrumbs="$breadcrumbs">
        {{ __($pageTitle) }}
    </x-page.header>
@endsection
@section('content')

<x-form :action="route('curriculum.themes.update',$themeId)">
    <div class="card">
        <div class="card-body">
            {{ __('Theme') }}
            <h3>{{ $data->title }}</h3>
            
            {{ __('Module') }}/{{ __('Section') }}
            <input class="custom-control-input" name="module" type="checkbox" id="module" value="1">
            <x-input.select id="grouped" name="grouped" :addEmpty='0' :selected="$data->grouped" class='select2' :items="$modules" disabled="disabled" />

            <h3>{{ __('Description') }}</h3>
            {{ $data->description }}
        </div>
    </div>
</x-form>
@endsection
@section("JS")
<script>
    $(function() {if($("#module").is(':checked')){$("#grouped").hide();}else{$("#grouped").show();}});
    $("#module").on("click",function(){$("#grouped").toggle('display');})
</script>
@endsection