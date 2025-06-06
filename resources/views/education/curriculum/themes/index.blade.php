@extends('layouts.dashboard')
@section("pageTitle")
    {{ __($pageTitle) }}
@endsection
@section("CSS")

@endsection
@section('content-header')
    <x-page.header :breadcrumbs="$breadcrumbs">
        {{ __($pageTitle) }}
    </x-page.header>
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-9">
            <div class="card">
                <div class="card-header border-0">
                    <div class="card-tools">
                        <a class="btn btn-tool" href="{{ route("curriculum.themes.create", ["subject"=>$subjectId]) }}"><i class="fa fa-plus"></i> {{ __('Create') }}</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        @isset($data['themes'][0])
                        @foreach ($data['themes'][0] as $item)
                            @if($item['module'] == 1)
                                <tr data-widget="expandable-table" aria-expanded="false">
                                    <td><i class="expandable-table-caret fas fa-caret-right fa-fw"></i> {{ $item['title'] }}</td>
                                    <td class='p-0 align-middle text-center btn-group-vertical'>
                                        <a href="{{ route('curriculum.themes.edit', $item['id']) }}" class="btn btn-flat btn-xs"><i class="fa fa-edit"></i></a>
                                        <a href="{{ route('curriculum.themes.create', ['subject'=>$subjectId,'grouped'=>$item['id']]) }}" class="btn btn-flat btn-xs text-success"><i class="fa fa-plus"></i></a>
                                    </td>
                                </tr>
                                <tr class="expandable-body d-none">
                                    <td>
                                        <div class="p-0">
                                            <table class="table table-hover">
                                                <tbody>
                                                    @isset($data['themes'][$item['id']])
                                                        @foreach ($data['themes'][$item['id']] as $theme)
                                                            <tr>
                                                                <td>{{ $theme['title'] }}</td>
                                                                <td class='align-middle text-center btn-group-vertical'>
                                                                    <a href="{{ route('curriculum.themes.edit', $theme['id']) }}" class="btn btn-flat btn-xs"><i class="fa fa-edit"></i></a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endisset
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td>{{ $item['title'] }}</td>
                                    <td class='align-middle text-center btn-group-vertical'>
                                        <a href="{{ route('curriculum.themes.edit', $item['id']) }}" class="btn btn-flat btn-xs text-primary"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>      
                            @endif
                        @endforeach
                        @endisset
                    </table>
                </div>
            </div>
        </div>
        <div class='col-xl-3'>
            <div class="card">
                <div class="card-body">
                    <dl>
                        <dt>{{ __("Subject") }}</dt>
                        <dd>{{ $data['title'] }}</dd>
                        <dt>{{ __("Short title") }}</dt>
                        <dd>{{ $data['short_title'] }}</dd>
                        <dt>{{ __("Description") }}</dt>
                        <dd>{{ $data['options']['description'] }}</dd>
                        @if($data['type'])
                        <dt>{{ __("Type") }}</dt>
                        <dd>{{ $data['type'][0]['name'] }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("JS")

@endsection