<table class="table datatable" style="width:100%">
    <thead>
        <tr>
            <th>{{ __("Subject") }}</th>
            <th>{{ __("Hours") }}</th>
            <th>{{ __("AVG") }}</th>
        </tr>
    </thead>
    @foreach ($subjectHours as $subject)
        <tr>
            <td>{{ $subject->subject->title }}</td>
            <td>{{ $subject->hours }}</td>
            <td>
                @if(isset($data->grades[15]))
                    {{ round($data->grades[15]->avg("grade"),0) }}
                {{-- @elseif(isset($data->grades[12])|| isset($data->grades[17]))
                    {{ round($data->grades[12]->avg("grade"),0) ?? round($data->grades[17]->avg("grade"),0) }} --}}
                @elseif(isset($data->grades[11]))
                    {{ round($data->grades[11]->avg("grade"),0) }}
                @else
                    {{-- {{ round($data->grades[10]->avg("grade"),0) }} --}}
                @endif
            </td>
        </tr>
    @endforeach
</table>

@push("js")

<script>
$(function(){
    $('.datatable').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            { extend:"excel", text:'<i class="fa fa-file-excel"></i>', className:"btn-success" },
            { extend:"pdf", text:'<i class="fa fa-file-pdf"></i>', className:"btn-danger" },
            { extend:"print", text:'<i class="fa fa-print"></i>', className:"btn-default" }
        ]
    } );
});
</script>
@endpush