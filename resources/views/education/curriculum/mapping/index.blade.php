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
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Documents</h3>
            <div class="card-tools">
                <a class="btn btn-tool" href="{{ route("curriculum.mapping.create",$subjectId) }}"><i class="fa fa-plus"></i></a>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped projects">
                <thead>
                    <tr>
                        <th>{{ __('Dates') }}</th>
                        <th>{{ __('Author') }}</th>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Hours') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th style="width:10%"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list as $item)
                        <tr>
                            <td>
                                <a>{{ __('Created') }}</a><br>
                                <small>{{  $PageHelper->date($item->created_at) }}</small>
                                @isset($item->updated_at)
                                    <br><a>{{ __('Last update') }}</a><br>
                                    <small>{{ $PageHelper->dateTime($item->updated_at) }}</small>
                                @endisset
                            </td>
                            <td>
                                <ul class="list-inline">
                                    <li class="list-inline-item">
                                        <img src="{{ $PageHelper->getUserAvatarPath($item->user_id) }}" alt="{{ $item->first_name }}" class="table-avatar">
                                    </li>
                                </ul>
                            </td>                                
                            <td>{{ Str::words($item->title, 10) }}</td>
                            <td>{{ $item->hours }}</td>
                            <td class="project-state">
                                <x-elements.badge :value="$item->value" >{{ $item->name }}</x-elements.badge>
                            </td>
                            <td>
                                <div class="btn-group">
                                    @if($item->value == 1)
                                        {{-- <a type="button" class="btn btn-primary btn-sm disabled"><i class="fas fa-folder"></i> {{ __("View") }}</a> --}}
                                        <a href="{{ route("curriculum.mapping.show",[$subjectId,$item->id]) }}" type="button" class="btn btn-success btn-sm"><i class="fas fa-eye"></i> {{ __("View") }}</a>
                                    @else
                                        <a href="{{ route("curriculum.mapping.edit",[$subjectId,$item->id]) }}" type="button" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i> {{ __("Edit") }}</a>
                                        <a type="button" class="btn btn-danger btn-sm disabled"><i class="fas fa-trash"></i>{{ __("Delete") }}</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">

        </div>
    </div>
@endsection