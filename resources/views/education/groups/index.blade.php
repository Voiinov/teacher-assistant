@extends('layouts.dashboard')
@section("pageTitle")
    {{ __($pageTitle) }}
@endsection
@section("CSS")
    <?php $PageHelper->addCSS("DataTables") ?>
@endsection
@section('content-header')
    <x-page.header :breadcrumbs="$breadcrumb">
        @isset($cardTitle) {{ $cardTitle }} @else {{ $pageTitle }} @endisset
    </x-page.header>
@endsection
@section('content')
    <div class="card card-primary card-tabs">
        <div class="card-header p-0 pt-1">
            @php
                $navElements = array_keys($groupsList);
                $navElements = array_combine($navElements,$navElements);
             @endphp
            <x-page.tabs.nav :title="__('Study year')" :elements="$navElements" />
        </div>
        <div class="card-body p-0">
            <div id="custom-tabs-two-tabContent" class="tab-content">
                @php $first = true @endphp
                @foreach ($navElements as $key => $course)
                    <x-page.tabs.tabpanel :course="$course" :active="$first">
                        <table class='table table-hover'>
                            <thead>
                                <tr>
                                    <th>{{ __("ID") }}</th>
                                    <th>{{ __("Group") }}</th>
                                    <th>{{ __("Master") }}</th>
                                    <th>{{ __("Class teacher") }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            @foreach ($groupsList[$course] as $groupID => $group)
                                <tr>
                                    <td class='text-muted'>{{ $groupID }}</td>
                                    <td class='text-bold'>{{ $group['groupIndex'] }}</td>
                                    <td>
                                        @isset($group['master'])
                                            @foreach ($group['master'] as $userID => $user)
                                                <a href="{{ route("users.show",$userID) }}" class="btn btn-outline-primary btn-block btn-sm"><i class="fa fa-user"></i> {{ $user['name'] }}</a>
                                            @endforeach
                                        @endisset
                                    </td>
                                    <td>
                                        @isset($group['curator'])
                                            @foreach ($group['curator'] as $userID => $user)
                                                <a href="{{ route("users.show",$userID) }}" class="btn btn-outline-primary btn-block btn-sm"><i class="fa fa-user"></i> {{ $user['name'] }}</a>
                                            @endforeach
                                        @endisset
                                    </td>
                                    <td>
                                        <div class="btn-group btn-block">
                                            <a href="{{ route("gradebooks.group",$groupID) }}" class='btn btn-outline-danger btn-sm'><i class="fa fa-book"></i></a>
                                            <a href="{{ route("groups.show",["group"=>$groupID,"page"=>"students"]) }}" class='btn btn-outline-primary btn-sm'><i class="fa fa-graduation-cap"></i></a>
                                            <a href="{{ route("groups.show",["group"=>$groupID,"page"=>"users"]) }}" class='btn btn-outline-success btn-sm'><i class="fa fa-users"></i></a>
                                            <a href="{{ route("groups.edit",$groupID) }}" class='btn btn-outline-warning btn-sm'><i class="fa fa-cogs"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </x-page.tabs.tabpanel>
                    @php $first = false @endphp
                @endforeach
            </div>
            <div id="studyYear1" class="tab-content"></div>
        </div>

    </div>
@endsection