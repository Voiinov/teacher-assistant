@extends('layouts.dashboard')
@section("pageTitle")
    {{ $pageTitle }}
@endsection
@section('content')
<div class="row">
<div class="col-md-3">
    <!-- Profile Image -->
    <div class="card card-primary card-outline">
      <div class="card-body box-profile">
        <div class="text-center">
          <img class="profile-user-img img-fluid img-circle" src="/storage/img/avatars/20240816_175601.jpg" alt="User profile picture">
        </div>

        <h3 class="profile-username text-center">{{ $data->last_name }} {{ $data->first_name }} {{ $data->middle_name }}</h3>

        <p class="text-muted text-center">{{ $data->role->implode("title",", ") }}</p>

        <ul class="list-group list-group-unbordered mb-3">
          <li class="list-group-item">
            <b>{{ __("Hired") }}</b> <a class="float-right"></a>
          </li>
        </ul>
        <a href="#" class="btn btn-primary btn-block"><b>{{ __("Timesheet review") }}</b></a>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->

    <!-- About Me Box -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">{{__("About me")}}</h3>
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
        <div class="card-header p-2">
            <ul class="nav nav-pills">
              <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">{{__("Activity")}}</a></li>
              <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">{{__("Timeline")}}</a></li>
              <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">{{__("Settings")}}</a></li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
                <div class='table-responsive'>
                    <table id="userHours" class='table table-hover'>
                        <thead>
                            <tr>
                                <th>{{ __("#")}}</th>
                                @for($day=1;$day<=date("t");$day++)
                                    <th>{{$day}}</th>
                                @endfor
                            </tr>
                        </thead>
                            @isset($timetable)
                                @foreach ($timetable as $time => $timeLine)
                                    <tr>
                                        <td>{{$time}}</td>
                                            @for($day=1;$day<=date("t");$day++)
                                            <td>
                                                @isset($timeLine[$day])
                                                    @foreach ($timeLine[$day] as $groupId => $lesson)
                                                        {{ $lesson->subject->short_title }}
                                                    @endforeach
                                                @endisset
                                            </td>
                                        @endfor
                                    </tr>
                                @endforeach
                            @endisset
                    </table>
                </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
                <!-- The timeline -->
                
              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane" id="settings">
                
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div>
    </div>
  </div>
</div>
@endsection
