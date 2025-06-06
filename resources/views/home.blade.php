@extends('layouts.dashboard')

@section('content')
<div class="card">
  <div class="card-body">
    {{ __("Current study year") }}: {{ $options->studyBegin()->year }}-{{ $options->studyEnd()->year }}
    {{ $options->studyBegin()-> diffForHumans("now") }} {{_("end")}}
  </div>
</div>
<div class="row">
  <div class="col-12 col-md-6">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">{{ __("Calendar") }}</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i>
          </button>
        </div>
        <!-- /.card-tools -->
      </div>
      <div class="card-body p-0">
        <div id='dayCalendar' style="height: 150px"></div>
      </div>
    </div>
  </div>
  <div class="col-12 col-md-6">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">{{ __("Subjects") }}</h4>
      </div>
      <div class="card-body p-0">
        <table class="table">
          @foreach ($subjects as $item)
            <tr>
              <td>{{ $item->subject->title }}</td>
              <td>{{ $item->group->index}}</td>
              <td>
                @isset($item->gradeBookActualOnly[0]->id)
                  <a href="{{ route("gradebook.show",["gradebook"=>$item->gradeBookActualOnly[0]->id,"subjectID"=>$item->subject_id]) }}" class='btn btn-block btn-primary btn-sm'>{{ __("Journal") }}</a></td>
                @endisset
            </tr>
          @endforeach
          <tr></tr>
        </table>
      </div>
    </div>

  </div>
</div>
<!-- /.content -->
@endsection

@section("CSS")
{{-- <link rel="stylesheet" href="{{ asset('plugins/fullcalendar/main.min.css') }}"> --}}
@endsection

@section("JS")
{{-- <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script> --}}
<script src="{{ asset('plugins/fullcalendar/index.global.min.js') }}"></script>
<script src="{{ asset('plugins/fullcalendar/locales/' . str_replace('_', '-', app()->getLocale()) .'.js') }}"></script>
<script src="{{ asset('plugins/fullcalendar/packages/google-calendar/index.global.min.js') }}"></script>
<script>
$(function (){
  var calendarEl = document.getElementById("dayCalendar"); // Отримуємо DOM-елемент

  var calendar = new FullCalendar.Calendar(calendarEl, { // Передаємо DOM-елемент у FullCalendar
      initialView: 'listDay',
      locale: '{{ str_replace('_', '-', app()->getLocale()) }}',
      footerToolbar:{
        start: '', // will normally be on the left. if RTL, will be on the right
        center: '',
        end: 'dayGridMonth,timeGridWeek,listDay,listWeek' // will normally be on the right. if RTL, will be on the left
      },
      googleCalendarApiKey:'{!! $data["googleCalendarApiKey"] !!}',
      events:  '{{ route("timetable.index.ajax",["user"=>Auth::id()]) }}',
      eventSources:[
            {
                "googleCalendarId":"m0im41odup88meongd7m3b5odg@group.calendar.google.com",
                "className":"bg-warning"
            },  
            {
                "googleCalendarId":"uk.ukrainian#holiday@group.v.calendar.google.com",
                "className":"bg-success"
            }]
  });

  calendar.render();
})
</script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>


@endsection