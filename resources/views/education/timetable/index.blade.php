@extends('layouts.dashboard')
@section("pageTitle")
    {{ __($pageTitle) }}
@endsection
@section("CSS")
    @php $PageHelper->addCSS("DataTables") @endphp
@endsection
@section('content-header')
    <x-page.header :breadcrumbs="$breadcrumb">
        {{ __($pageTitle) }}
    </x-page.header>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('Timetable') }}</h3>
        <div class="card-tools">
            <a class="btn btn-tool" href="{{ route('timetable.import') }}"><i class="fa fa-download"></i></a>
        </div>
    </div>
    <div class="card-body">
    </div>
</div>
@endsection

@section("JS")
<?php
$PageHelper->addJS("DataTables");
$PageHelper->addJS("jszip");
$PageHelper->addJS("pdfmake");
?>

@endsection