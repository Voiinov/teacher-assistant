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
<x-form :action="route('curriculum.themes.store',$subjectId)">
    <div class="card">
        <div class="card-body">
            {{-- {{ dd($errors) }} --}}
            <x-input.group label="{{ __('Theme') }}" for="title" :error="$errors->has('title') ? $errors->first('title') : null">
                <x-input.text name="title" :value="old('title')" requred />
            </x-input.group>
            <div class="custom-control custom-checkbox">
                <input class="custom-control-input" name="module" type="checkbox" id="module" value="1">
                <label for="module" class="custom-control-label">{{ __('Module') }}/{{ __('Section') }}</label>
            </div>

            <x-input.select id="grouped" name="grouped" :addEmpty='0' :selected="$grouped" class='select2' :items="$modules" />

            <x-input.group label="{{ __('Subject') }}" for="subject" :error="$errors->first('subject')">
                <x-input.select name="subject" class="" :items="$subjectsList" :selected="$subjectId" requred />
            </x-input.group>

            <x-input.group label="{{ __('Description') }}" for="description" :error="$errors->first('description')">
                <x-input.textarea name="description">
                    {{ old('description') }}
                </x-input.textarea>
            </x-input.group>
        </div>
        <div class="card-footer">
            <x-button.primary class='float-right' type="submit">
                {{ __('Create') }}
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