@extends('layouts.dashboard')
@section("pageTitle")
    {{ __($pageTitle) }}
@endsection
@section("CSS")

@endsection
@section('content-header')
    <x-page.header :breadcrumbs="$breadcrumb">
        {{ __($pageTitle) }}
    </x-page.header>
@endsection
@php
$counter = 1;
@endphp
@section('content')
{{-- {{ dd($data->toArray()) }} --}}
    <div class="card">
        <div class="card-header"></div>
        <div class="card-body p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{__("#")}}</th>
                        <th>{{__("Theme")}}</th>
                        <th>{{__("Description")}}</th>
                    </tr>
                </thead>
                @foreach ($data->themes as $i => $item)
                    @if(count($item)>0)
                        @foreach ($item as $lesson)
                            <tr>
                                @if($lesson['module']==1)
                                    <td colspan="3" class='text-center'>{{ $lesson['title'] }}</td>
                                    {{-- <td class='hidden' style=""></td>
                                    <td class='hidden' style=""></td> --}}
                                @else
                                    <td class='number'>{{ $i }}</td>
                                    <td>{{ $lesson['title'] }}</td>
                                    <td>{{ $lesson['description'] }}</td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class='number'>{{ $i }}</td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </div>
    </div>

@endsection