@extends('layouts.dashboard')
@section("pageTitle")
    {{ __($pageTitle) . " | " }}
@endsection
@section("CSS")

@endsection
@section('content-header')
    <x-page.header :breadcrumbs="$breadcrumb">
        {{ __($pageTitle) }}
    </x-page.header>
@endsection
@section('content')
<x-form :action="route('curriculum.mapping.store',$subjectId)">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-10">
                    <x-input.group label="{{ __('Subject') }}" for="subject_id" :error="$errors->first('subject_id')">
                        <x-input.select name="subject_id" class="select2" :items="$subjectsList" :selected="$subjectId" requred />
                    </x-input.group>
                </div>
                <div class="col-md-2">
                    <x-input.group label="{{ __('Hours') }}" for="hours" :error="$errors->first('hours')">
                        <x-input.number name="hours" value="{{ old('hours') }}" requred />
                    </x-input.group>
                </div>
            </div>
            <x-input.group label="{{ __('Title') }}" for="Title" :error="$errors->first('title')">
                <x-input.text name="title" value="{{ old('title') }}" requred />
            </x-input.group>
            <x-input.group label="{{ __('Description') }}" for="description" :error="$errors->first('description')">
                <x-input.textarea name="description">{{ old('description') }}</x-input.textarea>
            </x-input.group>
        </div>
        <div class="card-footer">
            <x-button.primary type="submit" class='float-right'>
                {{ __('Create') }}
            </x-button.primary>
        </div>
    </div>
</x-form>
<div class="callout callout-info">
    {{ __('information.curriculum.mapping') }}
</div>
@endsection
@section("JS")
@endsection