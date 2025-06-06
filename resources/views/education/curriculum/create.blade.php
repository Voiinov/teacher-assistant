@extends('layouts.dashboard')
@section("pageTitle")
    {{ __($pageTitle) . " | " }}
@endsection
@section("CSS")

@endsection
@section('content-header')
    <x-page.header :breadcrumbs="
        [['name'=>'Curriculum'],
        ['name'=>'Themes'],
        ['name'=>'Create','current'=>true]],
    ">
        {{ __($pageTitle) }}
    </x-page.header>
@endsection
@section('content')
<x-form :action="route('curriculum.store')">
    <div class="card">
        <div class="card-body">
            <x-input.group label="{{ __('Theme') }}" for="theme" :error="$errors->first('theme')">
                <x-input.text name="theme" :value="old('theme')" requred />
            </x-input.group>
            <x-input.group label="{{ __('Subject') }}" for="subject" :error="$errors->first('subject')">
                <x-input.select name="subject" class="select2" :items="$subjectsList" :selected="$subjectId" requred></x-input.group>
            </x-input.group>
            <x-input.group label="{{ __('Description') }}" for="description" :error="$errors->first('description')">
                <x-input.textarea name="description">
                    {{ old('description') }}
                </x-input.textarea>
            </x-input.group>
        </div>
        <div class="card-footer">
            <input type="hidden" name="action" value="storeTheme">
            <x-button.primary class='float-right'>
                {{ __('Create') }}
            </x-button.primary>
        </div>
    </div>
</x-form>
@endsection
@section("JS")

@endsection