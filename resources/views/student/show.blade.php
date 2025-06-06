@extends('layouts.dashboard')
@section("CSS")
{{ $PageHelper->addCSS("DataTables") }}
@endsection
@push("js")
{{ $PageHelper->addJS("inputmask")}}
{{ $PageHelper->addJS("dataTables")}}
{{$PageHelper->addJS("jszip")}}
{{$PageHelper->addJS("pdfmake")}}
@endpush
@section("pageTitle")
    {{ __($pageTitle) }}
@endsection
@section('content-header')
    <x-page.header :$breadcrumbs>
        @isset($cardTitle) {{ $cardTitle }} @else {{ $pageTitle }} @endisset
    </x-page.header>
@endsection
@section('content')
<div class="row">
    <div class="col-md-3 no-print">

        <!-- Profile Image -->
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
              <img class="profile-user-img img-fluid img-circle" src="{{ Storage::url("img/avatars/default.jpg") }}" alt="{{__("User profile picture")}}">
            </div>

            <h3 class="profile-username text-center">{{ $data->last_name }} {{ $data->first_name }}</h3>

            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>{{ __("Enrolled") }}</b> <a class="float-right">@isset( $data->expelled ){{ $data->enrolled->format("d.m.Y") }} @endisset</a>
              </li>
              <li class="list-group-item">
                <b>{{ __("Expelled") }}</b> <a class="float-right">@isset( $data->expelled ) {{ $data->expelled->format("d.m.Y") }} @endisset</a>
              </li>
              <li class="list-group-item">
                <b>{{ __("Average grade") }}</b> <a class="float-right"></a>
              </li>
            </ul>

            <a href="{{ route("groups.edit",["group"=> $data->group->id]) }}" class="btn btn-primary btn-block"><b>{{ __("Group") }}</b></a>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->

        <!-- About Me Box -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">{{__("About me")}}</h3>
            <div class="card-tools"></div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <strong><i class="fas fa-book mr-1"></i> {{__("Education")}}</strong>
            <p class="text-muted"></p>
            <hr>
            <strong><i class="fas fa-map-marker-alt mr-1"></i> {{__("Location")}}</strong>
            <p class="text-muted"></p>
            <hr>
            <strong><i class="fas fa-pencil-alt mr-1"></i> {{__("Skills")}}</strong>
            <p class="text-muted"></p>
            <hr>
            <strong><i class="far fa-file-alt mr-1"></i> {{__("Notes")}}</strong>
            <p class="text-muted"></p>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <div class="col-md-9">
        <div class="card">
          <div class="card-header p-2 no-print">
            <ul class="nav nav-pills">
              <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">{{ __("Activity")}}</a></li>
              <li class="nav-item"><a class="nav-link" href="#grades" data-toggle="tab">{{ __("Grades") }}</a></li>
              <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">{{ __("Settings") }}</a></li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div id="activity" class="active tab-pane">

              </div>
              <div id="grades" class="tab-pane table-responsive">
                @include("student.content.grades")
              </div>
              <div id="settings" class="tab-pane">
                @if(isset($data->group->groupSettsUsers[Auth::id()]) || $permnission)
                  @include("student.content.settings")
                @else
                  <h2 class='text-center text-muted'>{{ __("Access denied") }}!</h2>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
</div>
@endsection
