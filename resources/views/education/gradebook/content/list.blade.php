<div class="card">
    <div class="card-body">
        <h5>{{ __(":yearst year of study", ['year'=>$data['study_year']]) }}</h5>
        @isset($data['group']['curriculum']['title'])<p class='text-muted'>{{ __("За навчальною програмою") }} <a href='#'>{{$data['group']['curriculum']['title'] }}</a></p>@endisset
        <div class="row overlay-wrapper">
            <div class="overlay" style="display:none"><i class="fas fa-3x fa-sync-alt fa-spin"></i> <div class="text-bold pt-2">{{ __('Loading') }}...</div></div>
                <div class="col-5 col-sm-3">
                    <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                        @foreach ($data['group']['curriculum']['subjects'] as $item)
                        @php $hours = explode(",", $item['pivot']['hours']) @endphp
                        @if($hours[$data['study_year']]>0)
                        <a 
                            class="nav-link" 
                            style="width:100%"
                            id="vert-tabs-{{ $item['id'] }}-tab" 
                            data-toggle="pill" 
                            data-subject-id="{{ $item['id'] }}"
                            data-gradebook-id="{{ $data['id'] }}"
                            href="#vert-tabs-{{ $item['id'] }}" 
                            role="tab" 
                            aria-controls="vert-tabs-{{ $item['id'] }}" 
                            aria-selected="false">
                            <div class="row">
                                <div class="col-10 text-truncate">
                                    {{ $item['title'] }}
                                </div>
                                <div class="col-2">
                                    <x-elements.badge success>{{$hours[$data['study_year']]}}</x-elements.badge>
                                </div>
                            </div>
                        </a>
                        @endif
                        @endforeach
                    </div>
                </div>
                <div class="col-7 col-sm-9">
                    <div id="vert-tabs-tabContent" class="class tab-content">
                        <div style="width: 800px;margin: 0 auto;"></div>
                        <table class='table table-striped table-bordered nowrap' style="width:100%" id="gradebook" data-gradebook-id="{{ $data["id"] }}" data-subject-id="">
                            <thead>
                                <tr>
                                    <th>{{ __("Student") }}</th>
                                    @isset($grades['colls'])
                                    @foreach ($grades['colls'] as $timetable)
                                        @foreach ($timetable as $type => $lesson)
                                            <th>{{ $lesson }}</th>
                                        @endforeach
                                    @endforeach
                                    @endisset
                                    <th>AVG</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($data['students'] as $student)
                            @php $ARR[$student['id']] = ["last_name"=>$student['last_name'],"first_name"=>$student['first_name']] @endphp
                                <tr>
                                    <td>{{ $student['last_name'] }} {{ $student['first_name'] }}</td>
                                    @isset($grades['colls'])
                                        @foreach ($grades['colls'] as $lessonId => $timetable)
                                            @foreach ($timetable as $type => $lesson)
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
                                                            class='form-control form-control-border grade'
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
                                            @endforeach
                                        @endforeach
                                    @endisset
                                    <td>{{ $grades['data'][$student['id']]['avg'] ?? "" }}</td>
                                </tr>    
                            @endforeach
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </div>
</div>
@push("js")
<script>
function getGrades(subjectID, gradebook){
    gradebook.destroy();
    $('#gradebook').empty();

    $.ajax({
        url: "{{ route('gradebooks.group.subject.grades.ajax') }}",
        type: "GET",
        data:{
            "subjectID": subjectID,
            "open":"{{ $data['open'] }}",
            "closes":"{{ $data['close_semestr'] }}",
            "close":@empty($data['close_year']) "{{ date('Y-m-d 23:59:59') }}" @else "{{ $data['close_year'] }}" @endempty,
            "gradebookID": {{ $data['id'] }}
            }
    }).done(function(data) {
        let gradebookID = {{ $data['id'] }};
        let studentList = @php echo json_encode($ARR) @endphp;
        if (data.colls) {

            let studentsArray = data.data;
            let timetable = data.colls;
            let tableHtml = "<table id='gradebook' class='table table-striped table-bordered nowrap' style='width:100%'>";

            tableHtml += "<thead><tr><th>{{ __('Student') }}</th>";

            $.each(timetable, function(lessonid, colls) {
                $.each(colls['header'], function(key, coll) {
                    tableHtml += "<th>" + $.datepicker.formatDate('dd.mm', new Date(coll)) + "</th>";
                });
            });
            tableHtml += "<th>{{ __('AVG') }}</th>";
            tableHtml += "</tr></thead>";

            $.each(studentsArray, function(studentId, student) {
                tableHtml += "<tr><td>" + studentList[studentId].last_name + " " + studentList[studentId].first_name + "</td>";

                $.each(timetable, function(lessonId, lessonKeys) {
                
                    $.each(lessonKeys, function(key) {
                        tableHtml += "<td>"
                        if (student.grades[lessonId] && student.grades[lessonId][key]) {
                            tableHtml += student.grades[lessonId][key]['grade'];
                        }
                        tableHtml += "</td>";
                    });                            
                });
                tableHtml += "<td>" + student.avg + "</td>";
            });
            tableHtml += "</tr></table>";
            $("#vert-tabs-tabContent").html(tableHtml);
            
            $("#gradebook").DataTable({
                language: { url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/uk.json' },
                ordering: false,
                paging: false,
                fixedColumns: {start:1, end:1},
                scrollCollapse: true,
                scrollX: true,
                scrollY: 500
            });

        } else {
            $("#vert-tabs-tabContent").html("<p>{{ __('No students found for this gradebook') }} .</p>");
        }
        $(".overlay").toggle();
    });
}
</script>
    
@endpush
