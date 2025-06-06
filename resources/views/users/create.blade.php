@extends('layouts.app')
@section('content-header')
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ __('Students') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Legacy User Menu</li>
                </ol>
            </div>
        </div>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('Students list') }}</h3>
        <div class="card-tools">
            <a class="btn btn-tool" href="{{ route('student.create') }}"><i class="fa fa-plus"></i></a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('Last name') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Middle name') }}</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection