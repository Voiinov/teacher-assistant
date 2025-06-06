@section("pageTitle")
    {{ $pageTitle }}
@endsection
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __("Timetable on :date",["date"=>$date->translatedFormat("l jS, F Y")]) }}</h3>
        </div>
        <div class="card-body p-0 table-responsive">
            <table class='table table-hover'>
                <thead>
                    <tr>
                        <th>{{__("Time")}}</th>
                        <th>{{__("Subject")}}</th>
                        <th><i class="fas fa-door-open"></i></th>
                        <th><i class="fas fa-link"></i></i></th>
                    </tr>
                </thead>
                @foreach ($data as $lesson)
                    <tr>
                        <td class="@if($lesson->begin->isBefore($date) && $lesson->end->isAfter($date)){{"callout callout-danger bg-warning color-palette"}}
                        @elseif($lesson->end->isAfter($date)){{"callout callout-success"}}
                        @else{{"callout callout-default"}}@endif">
                        {{$lesson->begin->format("H:i")}} - {{$lesson->end->format("H:i")}}<br>
                            <small class="text-muted">{{$lesson->begin->diff($lesson->end)}}</small>
                        </td>
                        <td>{{$lesson->subject->title}}<br>
                            @isset($lesson->user->last_name)
                                <small class="text-muted"><i class="fa fa-user"></i> {{$lesson->user->first_name ?? null}} {{$lesson->user->last_name}}</small>
                            @endisset
                        </td>
                        <td></td>
                        <td>@isset($lesson->user->meet) <a href="{{ $lesson->user->meet }}" class='btn btn-primary btn-sm'>Перейти</a> @endisset</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <h5 class="mb-2">{{ __("We are online") }}</h5>
    @include("public.elements.links")

@endsection