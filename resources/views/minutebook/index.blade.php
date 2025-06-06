@extends('layouts.dashboard')
@section('content-header')
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ __('Minute book') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                    <li class="breadcrumb-item active">{{ __("Minute book") }}</li>
                </ol>
            </div>
        </div>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('Documents') }}</h3>
        <div class="card-tools">
            <a class="btn btn-tool" href="{{ route('minutebook.create') }}"><i class="fa fa-plus"></i></a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('Document date') }}</th>
                    <th>{{ __('Number') }}</th>
                    <th>{{ __('Document') }}</th>
                    <th>{{ __('Title') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($minuteBook as $item)
                    <td>{{ date("d.m.Y", strtotime($item->doc_date)) }}</td>
                    <td>{{ $item->number }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->title }}</td>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
@section("JS")

@endsection