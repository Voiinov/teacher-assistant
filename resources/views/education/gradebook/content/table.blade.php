
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __(":yearst year of study", ['year'=>$data['study_year']]) }}</h3>
    </div>
    <div class="card-body">
        {{ $subject->title }}
        @isset($data['group']['curriculum']['title'])<p class='text-muted'>{{ __("За навчальною програмою") }} <a href='#'>{{$data['group']['curriculum']['title'] }}</a></p>@endisset
        <div class="row overlay-wrapper">
            <div class="overlay" style="display:none"><i class="fas fa-3x fa-sync-alt fa-spin"></i> <div class="text-bold pt-2">{{ __('Loading') }}...</div></div>
            <div class="col-12">
                <div id="vert-tabs-tabContent" class="class tab-content">
                    <div style="width: 800px;margin: 0 auto;"></div>
                    <table class='table table-striped table-bordered nowrap' style="width:100%" id="gradebook" data-gradebook-id="{{ $data["id"] }}" data-subject-id="{{$subject->id}}">
                        <thead>
                            <tr>
                                <th>{{ __("Last name") }}, {{ __("Name") }}</th>
                                @isset($grades['colls'])
                                @foreach ($grades['colls'] as $lessonId=>$timetable)
                                    @foreach ($timetable["header"] as $type => $lesson)
                                        <th class="grade{{$type}}" data-lesson-id="{{$lessonId}}">@if($type=="_10"){{ $lesson->format("d.m") }}@else {{ $lesson }} @endif</th>
                                    @endforeach
                                @endforeach
                                @endisset
                                <th>AVG</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($data['students'] as $student)
                            <tr>
                                <td>{{ $student['last_name'] }} {{ $student['first_name'] }}</td>
                                @isset($grades['colls'])
                                    @foreach ($grades['colls'] as $lessonId => $timetable)
                                        @foreach ($timetable["header"] as $type => $lesson)
                                            
                                            @if($type == "_10")
                                                @php $date = $lesson @endphp
                                            @endif
                                            @if(!is_null($student['expelled']) && $date->greaterThanOrEqualTo($student['expelled']))
                                                <td class='bg-warning disabled color-palette text-center'></td>
                                            @elseif(!is_null($student['enrolled']) && $date->lessThan($student['enrolled']))
                                                <td class='bg-success disabled color-palette text-center'></td>
                                            @else
                                            <td>
                                                <input
                                                @isset($grades['data'][$student['id']]['grades'][$lessonId][$type]['user_id'])
                                                    @if($grades['data'][$student['id']]['grades'][$lessonId][$type]['user_id'] == Auth::id())
                                                        class='form-control form-control-border grade'
                                                        type="text" 
                                                        data-lesson-id="{{ $lessonId }}"
                                                        data-student-id="{{ $student['id'] }}"
                                                        data-grade-type="{{ $type }}"
                                                    @else
                                                        class='form-control form-control-border grade bg-gray disabled'
                                                        type="text" 
                                                        disabled=""
                                                    @endif
                                                    value="{{ $grades['data'][$student['id']]['grades'][$lessonId][$type]['grade'] ?? "" }}"
                                                @else
                                                    class='form-control form-control-border grade'
                                                    type="text" 
                                                    data-lesson-id="{{ $lessonId }}"
                                                    data-student-id="{{ $student['id'] }}"
                                                    data-grade-type="{{ $type }}"
                                                    class='form-control form-control-border grade'
                                                @endisset
                                                >
                                            </td>
                                            @endif
                                            
                                        @endforeach
                                    @endforeach
                                @endisset
                                <td>{{ $grades['data'][$student['id']]['avg'] ?? "" }}</td>
                            </tr>    
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>{{__("Label")}}</th>
                                @foreach ($grades['colls'] as $lessonId=>$timetable)
                                    @foreach ($timetable["header"] as $lesson)
                                        <th>{{ $timetable["footer"][0] ?? "" }}</th>
                                    @endforeach
                                @endforeach
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@push("modals")
<div class="modal fade" id="modal-default">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ __("Grade") }} <span></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="text-muted pl-3 pr-3"><strong>{{ __("Grade type") }}</strong></div>
                <ul class="todo-list ui-sortable grades-list" data-widget="todo-list" style="z-index: 10;" data-index=999></ul>
                <div class="btn-group btn-block">
                    <button type="button" class="btn btn-default btn-flat" data-toggle="dropdown">
                        <i class="fa fa-plus"></i>
                    {{-- {{__("Grade type")}} --}}
                    </button>
                    <div class="dropdown-menu" role="menu" style="width: 100%">
                        @foreach ($grades['gradeTypes'][11] as $id => $name)
                            <a class="dropdown-item grade-type" data-type-id="{{$id}}" href="#">{{$name}}</a>
                        @endforeach
                    </div>
                </div>
                <div class="label p-3 text-muted"><strong>{{ __("Label") }}</strong>
                    <div class="btn-group">
                        <button type="button" class="btn dropdown-toggle dropdown-icon" data-toggle="dropdown"></button>
                        <div class="dropdown-menu" role="menu">
                            @foreach ($grades['gradeTypes'][12] as $id => $name)
                                <a class="dropdown-item lesson-label" data-type-id="{{$id}}" href="#">{{$name}}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                    <ul class="todo-list lesson-labels" data-label="0" data-widget="todo-list" style="z-index: 10;" data-index=999></ul>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{__("Close")}}</button>
                <button type="button" class="btn btn-primary" id="save">{{__("Save changes")}}</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@endpush
@push("js")
<script>
    $(function (){

        let lessonId ="";
        let lessonLabel = 0;

        $(".todo-list").on('click', '.fa-trash', function () {
            if($(this).closest("ul").data("label") == 0){
                lessonLabel = 0;
            }
            $(this).closest('li').remove();
        });

        $("#modal-default .grade-type").on('click', function(){
            let typeId = $(this).data("type-id");
            let text = $(this).text();
            let li = `<li data-grade-type="${typeId}">
                <span class="handle ui-sortable-handle">
                    <i class="fas fa-ellipsis-v"></i>
                    <i class="fas fa-ellipsis-v"></i>
                </span>
                <span class="text">${text}</span>
                <div class="tools">
                    <i class="fas fa-trash" onlick="deleteTodoItem(this)"></i>
                </div>
                </li>`;
            $(".grades-list").append(li);
        });

        $("#modal-default .lesson-label").on('click', function(){
            lessonLabel = $(this).data("type-id");
            let text = $(this).text();
            let li = `<li>
                <i class="fa fa-label text-success"></i>
                <span class="text">${text}</span>
                <div class="tools">
                    <i class="fas fa-trash" onlick="deleteTodoItem(this)"></i>
                </div>
                </li>`;
            $(".lesson-labels").html(li);
        });

        $(".grade_10").on('click', function(){

            lessonId = $(this).data("lesson-id");
            
            $(".todo-list").html("");

            $("#modal-default .modal-title span").text($(this).text());
            $("#modal-default").modal('show');

        });

        $('#save').on('click', function(){
            var key = 1;
            var data = {
                "lessonId" : lessonId,
                "grades" : []
            };

            $(".ui-sortable li").each(function(){
                data.grades[key++] = $(this).data("grade-type");
            });

            if(data.grades.length > 0 || lessonLabel > 0){
                data.grades[0] = lessonLabel;
            }
            // console.log(data);

            $.ajax({
                url: '{{route('gradebook.grade.type.saveupdate.ajax')}}', // URL для запиту
                type: 'GET',
                data: data,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Для Laravel CSRF-токен
                },
                success: function(response) {
                    console.log('Response:', response); // Обробка відповіді
                    location.reload();
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText); // Обробка помилки
                }
            });



        });
    });

    $(".grade").on('change', function(){

$(".overlay").toggle();

let cell = $(this);

let grade = $(this).val().toUpperCase();

let gredebookId = $("#gradebook").data("gradebook-id");

let subjectId = $("#gradebook").data("subject-id");

if (grade === "0") {
    $(this).val("H");
}
if (grade === "H" || grade === "Н") {
    grade = 0;
}
if (grade === "-" || grade===null || grade==="" ) {
    grade = -1;
}

lesson = $(this).data('lesson-id');
student = $(this).data('student-id');
grade_type = $(this).data('grade-type').toString().replace(/_/g,"");

let token = '@csrf';
// token = token.substr(42, 40);

$.ajax({
    url: "{{ route('gradebook.grade.saveupdate.ajax') }}",
    type: "GET",
    data:{
        "lesson_id": lesson,
        "student_id":student,
        "grade_type":grade_type,
        "grade":grade,
        "gradebook_id":gredebookId,
        "subject_id":subjectId,
        "_token":token.substr(42, 40)
    },
    success: function(){
        if(grade === -1){
            cell.removeClass('is-valid');
            cell.removeClass('is-invalid');
            cell.addClass('is-warning');
        }else{
            cell.removeClass('is-warning');
            cell.removeClass('is-invalid');
            cell.addClass('is-valid');
        }
    }
}).done(function(){
    $(".overlay").toggle();
});
})
</script>
@endpush

@push('css')
   <style>
        .grade_10 {
            cursor: pointer;
        }
   </style>
@endpush
