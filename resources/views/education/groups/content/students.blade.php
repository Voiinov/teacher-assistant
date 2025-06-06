<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success elevation-1"><i class="far fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Дата відкриття групи</span>
                <span class="info-box-number">
                    {{ $data->open->format("d.m.Y") }}
                <small>{{ $data->open->translatedFormat("l") }}</small>
                </span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger elevation-1"><i class="far fa-times-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Дата закриття групи</span>
                <span class="info-box-number">
                    {{ $data->close->format("d.m.Y") }}
                <small>{{ $data->close->translatedFormat("l") }}</small>
                </span>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body p-0">
        <table class='table'>
            <thead>
                <tr>
                    <th>{{__("#")}}</th>
                    <th></th>
                    <th>{{__("Last name")}}</th>
                    <th>{{__("Name")}}</th>
                    <th>{{__("Middle name")}}</th>
                    <th>{{__("Birth date")}}</th>
                    <th>{{__("Enrolled")}}</th>
                    <th>{{__("Expelled")}}</th>
                </tr>
            </thead>
            @foreach ($data->students as $key=>$student)
                @if($student->expelled < $data->close)
                    <tr class='text-gray'>
                        <td>{{ ++$key }}</td>
                        <td><i class="fa fa-user-times text-danger"></i></td>
                        <td>{{ $student->last_name }}</td>
                        <td>{{ $student->first_name }}</td>
                        <td>{{ $student->middle_name }}</td>
                        <td></td>
                        <td>@if($student->enrolled != $data->open) {{ $student->enrolled->translatedFormat('d.m.y (D)') }} @endif</td>
                        <td>@if($student->expelled < $data->close) {{ $student->expelled->translatedFormat("d.m.y (D)") }} @endif</td>
                    </tr>
                @else
                <tr>
                    <td>{{ ++$key }}</td>
                    <td><i class="fa fa-user-check text-success"></i></td>
                    <td>{{ $student->last_name }}</td>
                    <td>{{ $student->first_name }}</td>
                    <td>{{ $student->middle_name }}</td>
                    <td>{{ $student->birthday->isoFormat("MMMM, D") }} ({{ $student->birthday->diff("now")->y }})</td>
                    <td>@if($student->enrolled != $data->open) {{ $student->enrolled->translatedFormat('d.m.y (D)') }} @endif</td>
                    <td></td>
                </tr>
                @endif
            @endforeach
        </table>
    </div>
</div>