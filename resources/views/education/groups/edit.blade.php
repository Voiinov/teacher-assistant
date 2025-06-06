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
@php $counter = 1; @endphp
@section('content')
{{-- {{dd($data->toArray())}} --}}
<div class="row">
    <div class="col-md-3">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <h3 class="profile-username text-center">{{ $data->indx }}</h3>
                <p class="text-muted text-center">
                    {{-- {{dd($data->toArray())}} --}}
                        {{ $data->groupSettsProfessions->implode("name","; ") }}
                </p>
                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                    <b>{{__("Open date")}}</b> <a class="float-right">{{ $data->open->format("d.m.Y") }}</a>
                    </li>
                    <li class="list-group-item">
                    <b>{{__("Close date")}}</b> <a class="float-right">{{ $data->close->format("d.m.Y") }}</a>
                    </li>
                    <li class="list-group-item">
                    <b>{{__("Study period")}}</b> <a class="float-right">{{ $data->open->diff($data->close) }}</a>
                    </li>
                    <li class="list-group-item">
                    <b>{{__("As of")}}</b> <a class="float-right">{{ $data->now->format("d.m.Y H:i") }}</a>
                    </li>

                </ul>
            </div>
        </div>
        <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">{{__("Profession")}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @foreach ($data->groupSettsProfessions as $prof)
                    <strong><i class="{{$prof->design['i']}} mr-1"></i> {{ $prof->kp }}</strong>
                    <p class="text-muted">{{ $prof->name }}</p>
                    <hr>
                @endforeach
                <strong class=''><i class="fa fa-users mr-1"></i> {{ __("Education seekers") }} <span class="badge badge-primary"></span></strong>
                <p>
                    <span class="badge badge-success"><i class="fa fa-user"></i> {{$data->studentsCounter['active'] }}</span>
                    <span class="badge badge-warning"><i class="fa fa-user-clock"></i> {{$data->studentsCounter['en'] }}</span>
                    <span class="badge badge-danger"><i class="fa fa-user-times"></i> {{$data->studentsCounter['ex'] }}</span>
                </p>
              
            </div>
            <!-- /.card-body -->
          </div>  
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">{{__("Education seekers")}}</a></li>
                </ul>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>{{__("Last name")}}</th>
                            <th>{{__("Name")}}</th>
                            <th>{{__("Middle name")}}</th>
                            <th>{{__("Enrolled")}}</th>
                            <th>{{__("Expelled")}}</th>
                            <th></th>
                        </tr>
                    </thead>
                    @foreach ($data->students as $student)
                        <tr>
                            <td>@if($data->inList) {{$counter++}} @endif </td>
                            <td>@if($student->gender=="f") <i class="fas fa-male text-primary"></i> @else <i class="fas fa-female text-pink"></i> @endif</td>
                            <td>{{ $student->last_name }}</td>
                            <td>{{ $student->first_name }}</td>
                            <td>{{ $student->middle_name }}</td>
                            <td>{{ $student->enrolled->greaterThanOrEqualTo($data->now) ? $student->enrolled->format("d.m.Y") : "" }}</td>
                            <td>{{ $student->expelled->lessThan($data->now) ? $student->expelled->format("d.m.Y") : "" }}</td>
                            <td><a href="{{ route("student.show",$student->id) }}" class="btn btn-info btn-sm"><i class="fa fa-user"></i></a></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
