@extends('layouts.dashboard')

@section("pageTitle")
    {{ __($pageTitle) }}
@endsection

@section("CSS")
    @php $PageHelper->addCSS("DataTables") @endphp
@endsection

@section('content-header')
    @isset($breadcrumb)
        <x-page.header :breadcrumbs="$breadcrumb">
            @isset($cardTitle) {{ $cardTitle }} @else {{ $pageTitle }} @endisset
        </x-page.header>
    @endisset
@endsection

@section('content')
@switch($page)
    @case("students")
        @include("education.groups.content.students")
        @break
    @case("users")
        @include("education.groups.content.users")
        @break
    @default

        
@endswitch

@endsection