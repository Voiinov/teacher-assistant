@section("pageTitle")
    {{ $pageTitle }}
@endsection
@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ __("Group") }}: {{ $groupData->groupIndex }}</h4>
            </div>
            <div class="card-body p-0">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">{{__("Profession")}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @foreach ($groupData->groupSettsProfessions as $prof)
                    <strong><i class="{{$prof->design->i ?? ""}} mr-1"></i> {{ $prof->kp }}</strong>
                    <p class="text-muted">{{ $prof->name }}</p>
                    <hr>
                @endforeach
            </div>
            <!-- /.card-body -->
        </div>  
        @isset($groupData->groupSettsUsers)
        @foreach ($groupData->groupSettsUsers as $user)
            <div class="card card-widget widget-user-2 shadow-sm">
                <div class="widget-user-header bg-warning">
                    <div class="widget-user-image">
                      <img class="img-circle elevation-2" src="/storage/img/avatars/20240816_175601.jpg" alt="{{$user->name}}">
                    </div>
                    <!-- /.widget-user-image -->
                    <h3 class="widget-user-username">{{$user->last_name}} {{ $user->first_name }}</h3>
                    <h5 class="widget-user-desc">@if($user->pivot->name == "master") {{__("Master")}}  @else {{ __("Class teacher") }} @endif</h5>
                </div>
            </div>
        @endforeach
    @endisset
    </div>
</div>

{{-- {{dd($data)}} --}}
@endsection

@section("JS")
<script src="{{ asset('plugins/fullcalendar/index.global.min.js') }}"></script>
<script src="{{ asset('plugins/fullcalendar/packages/google-calendar/index.global.min.js') }}"></script>
<script src="{{ asset('plugins/fullcalendar/locales/' . str_replace('_', '-', app()->getLocale()) .'.js') }}"></script>

<script>
    
    $(function (){
      var calendarEl = document.getElementById("calendar"); // Отримуємо DOM-елемент
    
      var calendar = new FullCalendar.Calendar(calendarEl, { // Передаємо DOM-елемент у FullCalendar
        initialView: 'listDay',
        dayMaxEventRows: true,
        views: {
            timeGrid: {
            dayMaxEventRows: 6 // adjust to 6 only for timeGridWeek/timeGridDay
            }
        },
        startTime: '{!! $data["workTime"]["startTime"] !!}',
        endTime: '{{ $data["workTime"]["endTime"] }}',
        locale: '{{ str_replace('_', '-', app()->getLocale()) }}',
        footerToolbar:{
        end: 'dayGridMonth,timeGridWeek,listWeek,timeGridDay' // will normally be on the right. if RTL, will be on the left
        },
        googleCalendarApiKey:'{!! $data["googleCalendarApiKey"] !!}',
        eventSources:[
            '{!! $data["route"] !!}',
            {
                "googleCalendarId":"uk.ukrainian#holiday@group.v.calendar.google.com",
                "className":"bg-success"
            }
        ],
        eventClick: function(ev) {
            ev.jsEvent.preventDefault();
        }
      });
    
      calendar.render();
    })
      
    </script>

@endsection
