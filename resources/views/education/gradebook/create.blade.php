@extends('layouts.dashboard')
@section("pageTitle")
    {{ __($pageTitle) . " | " }}
@endsection
@section("CSS")

@endsection
@section('content-header')
    <x-page.header :$breadcrumbs>
        {{ $pageTitle }}
    </x-page.header>
@endsection
@section('content')
<div class="col-md-4">
    
</div>
@endsection
@section("JS")

@endsection