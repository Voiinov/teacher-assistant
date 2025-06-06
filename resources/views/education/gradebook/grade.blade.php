<?php 
//vars
$beginTimeStamp = strtotime($data->begin);
$endTimeStamp = strtotime($data->end);
$beginDate = date("Y-m-d", $beginTimeStamp);
$endDate = date("Y-m-d", $endTimeStamp);

?>
@extends('layouts.dashboard')
@section("pageTitle")
    {{ $pageTitle }}
@endsection
@section("CSS")
<style>
.dbi {
    position: relative;
    overflow: hidden; /* Щоб приховати текст, що виходить за межі блоку */
}

.dbi::before {
    content: attr(data-placeholder);
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    /* font-size: 3rem; */
    color: rgba(0, 0, 0, 0.2); /* Напівпрозорий колір для фону */
    z-index: 0;
    white-space: nowrap; /* Запобігає переносу тексту */
}

.dbi select {
    min-width: 75px;
    position: relative;
    z-index: 1; /* Щоб select був поверх тексту */
    background: white;
}
</style>
@endsection
@section('content-header')
    <x-page.header :$breadcrumbs>
        {{ $headerTitle }}  @if($headerSubTitle) <small class='text-muted'>{{ $headerSubTitle }}</small> @endif
    </x-page.header>
@endsection
@section('content')
@if($gradeBook)
{{-- {{ dd($data->toArray()) }} --}}
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-1 col-sm-2"><a href="" type="button" class='btn btn-block btn-outline-warning btn-lg disabled'><i class="fa fa-arrow-left"></i></a></div>
            <div class="col-md-10 col-sm-8">
                <div class="row">
                    <div class="col-4 text-muted">
                        <small>{{ date("H:i", $beginTimeStamp) }}</small>
                    </div>
                    <div class="col-4 text-success text-center"><strong>{{ $lessonN }}</strong></div>
                    <div class="col-4 text-muted text-right">
                        <small>{{ date("H:i", $endTimeStamp) }}</small>
                    </div>
                    <div class='col-12'>
                        <div class="progress mb-3">
                            <div id="timer" class="progress-bar bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                {{-- <span>Урок закінчився!</span> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-1 col-sm-2"><a href="" class='btn btn-block btn-outline-success btn-lg disabled'> <i class="fa fa-arrow-right"></i></a></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card card-tabs">
            <div class="overlay" style="display:none">
                <i class="fas fa-2x fa-sync fa-spin"></i>
            </div>
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">{{ __("Grades") }}</h3>
                <ul class="nav nav-pills ml-auto p-2">
                  <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">{{__("Сurrent")}}</a></li>
                  <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">{{__("Thematic")}}</a></li>
                  <li class="nav-item"><a class="nav-link" data-card-widget="maximize"><i class="fas fa-expand"></i>
                  </a></li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fas fa-cog"></i> <span class="caret"></span>
                    </a>
                    <div class="dropdown-menu" style="">
                        <a href="#" class="dropdown-item disabled"><i class="fa fa-info-circle text-info"></i> Про групу</a>
                      <div class="dropdown-divider"></div>
                      @isset($data->subject->isSubgroup->id)
                      <a href="{{ route("gradebook.show",["gradebook"=>$gradeBook->id,
                        "subjectID"=> $data->subject->isSubgroup->id, "sub"=>$data->subject->id]) }}" class="dropdown-item"><i class="fa fa-book text-muted"></i> {{ __("Journal") }}</a>
                      @else
                      <a href="{{ route("gradebook.show",["gradebook"=>$gradeBook->id,
                        "subjectID"=> $data->subject->id]) }}" class="dropdown-item"><i class="fa fa-book text-muted"></i> {{ __("Journal") }}</a>
                      @endisset                      
                    </div>
                  </li>
                </ul>
              </div>
            <div class="card-body table-responsive p-0">
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <table class='table text-nowrap table-hover'>
                            <tr class="th">
                                <th style='width:20px'></th>
                                <th>{{ __("Name") }}</th>
                                <th colspan="2">{{ __("Grade") }}</th>
                            </tr>
                            @foreach ($data->group->students as $studentID => $student)
                                @if($student->expelled > $data->begin || is_null($student->expelled))
                                    @if($student->enrolled <= $beginDate || is_null($student->enrolled))
                                        @php
                                            $checked="checked";
                                            $isDisabled = false;
                                            $hide="";
                                            $class="";
                                            $G = $gradesNet['grades'];
                                        @endphp
                                        @isset($data->grades[$studentID])
                                            @if($data->grades[$studentID]->grade == 0)
                                                @php 
                                                    $checked="";
                                                    $isDisabled=true;
                                                    $hide="display:none";
                                                    $class="text-muted";
                                                @endphp
                                            @else
                                                @php $G[$data->grades[$studentID]->grade] = "selected" @endphp
                                            @endisset
                                        @endisset
                                        <tr id="data-student-{{ $studentID }}" class="{{$class}}">
                                            <td><div class="form-group">
                                                <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                                  <input
                                                    id="student{{$studentID}}"  
                                                    type="checkbox" class="custom-control-input grade-test" 
                                                    data-student-id="{{ $studentID }}"
                                                    data-grade-type="10"
                                                    value="0"
                                                    {{ $checked }}
                                                    >
                                                  <label class="custom-control-label" for="student{{$studentID}}"></label>
                                                </div>
                                              </div>
                                            </td>
                                            <td class='pb-0'>
                                                {{ $student->last_name }} {{ $student->first_name }}
                                                <div class='p-0'><small>@foreach ($student->labels as $class) <i class="{{$class}}"></i> @endforeach</small></div>
                                            </td>
                                            <td>
                                                <div style="display:none">
                                                0<sup><i class="fa fa-caret-up text-success"></i></sup>
                                                <sup><i class="fa fa-caret-down text-danger"></i></sup>
                                                <sup><i class="fa fa-caret-left text-muted"></i></sup>
                                                </div>
                                            </td>
                                            <td class='dbi' data-placeholder="@if($student->gender=="f") Відсутня @else Відсутній @endif">
                                                <select class="form-control form-control-sm select2 select2bs4 grade" 
                                                    id="studentGrade{{$studentID}}" 
                                                    data-lesson-id="{{ $lessonId }}"
                                                    data-student-id="{{ $studentID }}"
                                                    data-grade-type="10"
                                                style="width: 100%;{{$hide}}" @disabled($isDisabled)>
                                                    <option title="" value="-1">&nbsp;</option>
                                                        @foreach ($gradesNet['byGroups'] as $level=>$grades)
                                                            <optgroup label="{{__($level .' level')}}">
                                                                @foreach($grades as $grade)
                                                                    <option {{ $G[$grade] }} value="{{ $grade }}">{{ $grade }}</option>
                                                                @endforeach        
                                                            </optgroup>
                                                        @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @else
                                        @php $outOfList[$studentID] = $studentID; @endphp
                                    @endif
                                @else
                                    @php $outOfList[$studentID] = $studentID; @endphp
                                @endif
                            @endforeach
                        </table>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_2">
                        Поки нема тут нічого цікавого
                    </div>
                  </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-6 text-success"><i class="fa fa-user-check"></i> <span class='present'></span></div>
                    <div class="col-6 text-danger"><i class="fa fa-user-times"></i> <span class="absent"></span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        @isset($outOfList)
            <div class="card collapsed-card">
                <div class="card-header">
                    <h4 class="card-title"><i class="fa far fa-user-slash"></i> {{__("Out of list")}}</h4>
                    <div class="card-tools">
                        <span title="3 New Messages" class="badge badge-danger"><?= count($outOfList) ?></span>
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __("Name") }}</th>
                                <th>{{ __("Enrolled") }}</th>
                                <th>{{ __("Expelled") }}</th>
                            </tr>
                        </thead>
                        @foreach ($outOfList as $studentId)
                        @php
                            $enrolled = !empty($data->group->students[$studentId]->enrolled) ? \Carbon\Carbon::parse($data->group->students[$studentId]->enrolled) : \Carbon\Carbon::parse($data->group->open) ;
                            $expelled = !empty($data->group->students[$studentId]->expelled) ? \Carbon\Carbon::parse($data->group->students[$studentId]->expelled) : \Carbon\Carbon::parse($data->group->close) ;
                            // $expelled = \Carbon\Carbon::parse($data->group->students[$studentId]->expelled);
                        @endphp
                            <tr>
                                <td>{{ $data->group->students[$studentId]->last_name . " " . $data->group->students[$studentId]->first_name }}</td>
                                <td>{{ $enrolled->isoFormat("L (dd)") }}</td>
                                <td>{{ $expelled->isoFormat("L (dd)") }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        @endisset
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><i class="far fa-comments text-muted"></i> Урок пояснення нового матеріалу</h4>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                    <div class="btn-group">
                        <button type="button" class="btn btn-tool dropdown-toggle disabled" data-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cog"></i></button>
                        <div class="dropdown-menu dropdown-menu-right" role="menu" style="">
                            <a href="#" class="dropdown-item"><i class="far fa-comments text-muted"></i> Урок пояснення нового матеріалу</a>
                            <a href="#" class="dropdown-item"><i class="far fa-check-circle text-muted"></i> Урок закріплення знань</a>
                            <a href="#" class="dropdown-item"><i class="fas fa-layer-group text-muted"></i> Урок узагальнення та систематизації знань</a>
                            <a href="#" class="dropdown-item"><i class="fas fa-cogs text-muted"></i> Урок практичного застосування знань</a>
                            <a href="#" class="dropdown-item"><i class="fas fa-tasks text-muted"></i> Контрольний урок</a>
                            <a href="#" class="dropdown-item"><i class="fas fa-sync text-muted"></i></i> Урок змішаного типу</a>
                            <a href="#" class="dropdown-item"><i class="fas fa-exchange-alt text-muted"></i> Інтегрований урок</a>
                            <a href="#" class="dropdown-item"><i class="far fa-compass text-muted"></i> Урок-екскурсія</a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-default"><i class="fa fa-info-circle text-info"></i> Довідка</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <dd>
                    <dt>{{__("Theme")}}</dt>
                    <dd></dd>
                </dd>
            </div>
            <div class="card-footer p-0">
                <table class='table small'>
                    <thead>
                        <tr>
                            <th>Початок</th>
                            <th>№</th>
                            <th>Тривалість</th>
                            <th>К-ть</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{  $data->begin->translatedFormat("g:i a, l jS F Y") }}</td>
                            <td>{{ $lessonCounter }}</td>
                            <td>{{ $lessonTime }} {{ __("min.")}}</td>
                            <td>{{ $data->group->students->count() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@section("JS")
<script>
    @if($beginDate == date("d.m.Y"))
        // Налаштування часу початку та закінчення
        const startTime = new Date();
        startTime.setHours(18, 5, 0, 0); // Початок о 8:00
        const endTime = new Date();
        endTime.setHours(19, 25, 0, 0); // Кінець о 9:00
        // Запуск оновлення кожну хвилину
        progressBarTimer(); // Оновлення під час завантаження сторінки
        setInterval(progressBarTimer, 1000);
    @endif

    $(function(){

        studentsCounter();

        $("input[type=checkbox].grade-test").on("change", function () {
            var stidentId = $(this).data("student-id");
            var student = $(this).data('student-id');
            var grade = $(this).val();
            var grade_type = $(this).data('grade-type');
            var cell = $("#studentGrade"+stidentId);
            if ($(this).is(":checked")) {
                cell.prop("disabled", false)
                    .show();
                cell.parents("tr").removeClass("text-muted");
                    saveUpdate(student, grade_type,-1);
            } else {
                cell.prop("disabled", true)
                    .hide();
                cell.parents("tr").addClass("text-muted");
                saveUpdate(student, grade_type,0);
            }
            studentsCounter();
        });

        $(".grade").on('change', function(){

            student = $(this).data('student-id');
            grade = $(this).val();
            grade_type = $(this).data('grade-type');

            saveUpdate(student, grade_type,grade);
        
        })
    })

    function studentsCounter(){
        var absent = 0;
        var present = 0;
        $("input[type=checkbox].grade-test").each(function(){
            if($(this).is(":checked")){
                present++;
            }else{
                absent++;
            }
        });
        $("span.present").html(present);
        $("span.absent").html(absent);
    }

    function saveUpdate(student, type, grade){
        let token = '@csrf';
        $.ajax({
            url: "{{ route('gradebook.grade.saveupdate.ajax') }}",
            type: "GET",
            data:{
                "lesson_id": "{{$lessonId}}",
                "student_id":student,
                "grade_type":type,
                "grade":grade,
                "gradebook_id":"{{ $gradeBook->id }}",
                "subject_id":"{{ $data->subject->id }}",
                "_token":token.substr(42, 40)
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $(".overlay").show();
            },
        }).done(function(){
            $(".overlay").hide();
        });
    }

</script>
@endsection
@else
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="div card-body"><h2 class='text-center text-danger'>{{ __("message.gradebook.404") }}</h2></div>
            </div>
        </div>
    </div>
@endif
<x-modal size="lg">
<x-modal.title></x-modal.title>
<x-modal.body>
    <ul>
        <li><strong>Урок пояснення нового матеріалу:</strong> Основна мета - знайомство учнів з новою інформацією та поняттями.</li>
        <li><strong>Урок закріплення знань:</strong> Спрямований на повторення та закріплення раніше вивченого матеріалу.</li>
        <li><strong>Урок узагальнення та систематизації знань:</strong> Допомагає учням об'єднати окремі знання в цілісну систему.</li>
        <li><strong>Урок практичного застосування знань:</strong> Передбачає виконання практичних завдань, лабораторних робіт чи вправ.</li>
        <li><strong>Контрольний урок:</strong> Призначений для перевірки знань, умінь та навичок учнів (наприклад, контрольні роботи, тестування).</li>
        <li><strong>Урок змішаного типу:</strong> Поєднує кілька типів діяльності, наприклад, пояснення нового матеріалу та закріплення знань.</li>
        <li><strong>Інтегрований урок:</strong> Залучає матеріал з кількох предметів, щоб створити цілісне бачення певної теми.</li>
        <li><strong>Урок-екскурсія:</strong> Відбувається поза класом і включає вивчення матеріалу в реальних умовах (наприклад, екскурсія до музею).</li>
    </ul>
</x-modal.body>
</x-modal>
@endsection