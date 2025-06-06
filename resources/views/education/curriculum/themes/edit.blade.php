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
    <input class="input" style="display: none" name="subject" type="hidden" value="{{ $subjectId }}" />
    <div class="card">
        <div class="card-body">
            <x-input.group label="{{ __('Theme') }}" for="title">
                <x-input.text name="title" :value="$data->title" requred />
            </x-input.group>
            @if($data->module==0)
                <div>
                    <label for="module" class="label">{{ __('Module') }}/{{ __('Section') }}</label>
                    <input class="input" style="display: none" name="module" type="hidden" id="module" value="1" />
                </div>
                <x-input.select id="grouped" name="grouped" :addEmpty='0' :selected="$data->grouped" class='select2' :items="$modules" />
            @endif
            <x-input.group label="{{ __('Description') }}" for="description">
                <x-input.textarea name="description">
                    {{ $data->description }}
                </x-input.textarea>
            </x-input.group>
        </div>
        <div class="card-footer">
            <x-button.primary class='float-right' type="submit">
                {{ __('Save') }}
            </x-button.primary>
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