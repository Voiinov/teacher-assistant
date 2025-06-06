@extends('layouts.dashboard')
@section("pageTitle")
    {{ __($pageTitle) . " | " }}
@endsection
@section("CSS")
<style>
    .hidden{
        display: none;
    }
</style>
@endsection
@section('content-header')
    <x-page.header :breadcrumbs="$breadcrumb">
        {{ __($pageTitle) }}
    </x-page.header>
@endsection

@section('content')
{{-- {{dd($data, $themesList)}} --}}
<?php 
$lessonNumber = 1;
$row = 0;
// $themeCheck = 0;
 ?>
<div class="row">
    <div class="col-12">
        <div id="plan" class='list'>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="far fa-comment"></i> {{ $data->title }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4"><dl>
                                <dt><i class="fas fa-graduation-cap"></i> {{ __("Subject") }}</dt>
                                <dd>{{ $data->subject->title }}</dd>
                                <dt><i class="far fa-clock"></i> {{ __("Hours") }}</dt>
                                <dd>{{ $data->hours }}</dd>
                        </dl></div>
                        {{-- <div class="col-md-6">
                            <strong>Розподілено годин</strong>
                            <div class="row">
                                <div class="col-md">0</div>
                            </div>
                            <div class="progress mb-3">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%">
                                  <span class="sr-only">40% Complete (success)</span>
                                </div>
                              </div>
                        </div> --}}
                        <div class="col-md-8"><dl>
                                <dt><i class="fas fa-book-open"></i> {{ __("Description") }}</dt>
                                <dd>{!! \Illuminate\Support\Str::words($data['description'], 30,'...')  !!}</dd>
                        </dl></div>
                    </div>

                    <hr>
                    <table class="table table-hover" id="mappingTable">
                        <thead>
                            <tr>
                                <th></th>
                                <th style="width: 20px">{{ __("#") }}</th>
                                <th>{{ __("Theme") }}</th>
                                <th style="width:20px"></th>
                            </tr>
                        </thead>

                        @foreach ($data->themes as $i => $item)
                            @if(count($item)>0)
                                @foreach ($item as $number => $lesson)
                                    <tr id="row-{{$row}}">
                                        @if($number<0)
                                            <td style="display:none">{{$row++}}</td>
                                            <td colspan="3" class='text-center'>{{ $lesson['title'] }}</td>
                                            <td class='hidden' style=""></td>
                                            <td class='hidden' style=""></td>
                                        @else
                                            <td style="display:none">{{$row}}</td>
                                            <td class='number'>{{ $lessonNumber }}</td>
                                            <td id="lesson_{{ $lessonNumber }}" data-toggle="modal" data-target="#modal-themes" data-lesson="{{$lessonNumber++}}" data-row="{{$row++}}" style="cursor: pointer">
                                                {{ $lesson['title'] }}
                                            </td>
                                            <td>
                                                <x-elements.badge class="badge-success">{{ __("Saved") }}</x-elements.badge>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr id="row-{{$row}}">
                                    <td style="display:none">{{$row}}</td>
                                    <td class='number'>{{ $lessonNumber }}</td>
                                    <td id="lesson_{{ $lessonNumber }}" data-toggle="modal" data-target="#modal-themes" data-lesson="{{$lessonNumber++}}" data-row="{{$row++}}" style="cursor: pointer"></td>
                                    <td></td>
                                </tr>
                            @endif
                        @endforeach

                        {{-- @for ($i=1; $i<=$data->hours; $i++)
                            @if(isset($themesLessonList[$i]))
                                @foreach ($themesLessonList[$i] as $lesson)
                                <tr id="row-{{$row}}">
                                    @if($lesson['module']==1)
                                        <td style="display:none">{{$row++}}</td>
                                        <td colspan="3" class='text-center'>{{ $lesson['title'] }}</td>
                                        <td class='hidden' style=""></td>
                                        <td class='hidden' style=""></td>
                                    @else
                                    @php $themeCheck = 1; @endphp
                                    <td style="display:none">{{$row}}</td>
                                        <td class='number'>{{ $i }}</td>
                                        <td id="lesson_{{ $i }}" data-toggle="modal" data-target="#modal-themes" data-lesson="{{$i}}" data-row="{{$row++}}" style="cursor: pointer">
                                            {{ $lesson['title'] }}
                                        </td>
                                        <td>
                                            <x-elements.badge class="badge-success">{{ __("Saved") }}</x-elements.badge>
                                        </td>
                                    @endif
                                </tr>
                                @endforeach
                                @if($themeCheck==0)
                                    <tr id="row-{{$row}}">
                                        <td style="display:none">{{$row}}</td>
                                        <td class='number'>{{ $i }}</td>
                                        <td id="lesson_{{ $i }}" data-toggle="modal" data-target="#modal-themes" data-lesson="{{$i}}" data-row="{{$row++}}" style="cursor: pointer"></td>
                                        <td></td>
                                    </tr>
                                @endif
                            @else
                            <tr id="row-{{$row}}">
                                <td style="display:none">{{$row}}</td>
                                <td class='number'>{{ $i }}</td>
                                <td id="lesson_{{ $i }}" data-toggle="modal" data-target="#modal-themes" data-lesson="{{$i}}" data-row="{{$row++}}" style="cursor: pointer"></td>
                                <td></td>
                            </tr>
                            @endif
                        @endfor --}}
                    </table>
                </div>
                <div class="card-footer">
                    <x-form :action="route('curriculum.store')"><input type="hidden" name="options" id="postData" value=""></x-form>
                    {{-- <button id="saveData" class="btn btn-primary">{{ __("Save") }}</button> --}}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->
<x-modal.default id="modal-themes" size="xl">
    <x-modal.title>TITLE</x-modal.title>
    <x-modal.body class="p-4">
        <input type="hidden" id="lesson" value="">
        <select id='themesListSelect' class="form-control select2bs4" style="width: 100%;">
            <option title="" value="0">&nbsp;</option>
            @isset($themesList[0])
            <optgroup label="{{__('Themes')}}">
                @foreach ($themesList[0] as $item)
                    <option title="T" value="{{ $item->id }}">{{ $item->title }}</option>
                @endforeach
            </optgroup>
            @endisset
            @isset($themesList[1])
            <optgroup label="{{__('Module')}}">
                @foreach ($themesList[1] as $item)
                    <option title="M" value="{{ $item->id }}">{{ $item->title }}</option>
                @endforeach
            </optgroup>
            @endisset
        </select>
    </x-modal.body>
    <x-modal.footer custom>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Close') }}</button>
            {{-- <button type="button" id="addThemeToCM" data-dismiss="modal" class="btn btn-primary">{{ __('Save') }}</button> --}}
            <button type="button" id="saveData" data-dismiss="modal" class="btn btn-primary">{{ __('Save') }}</button>
        </div>
    </x-modal.footer>
</x-modal.default>
  <!-- /.modal -->

@endsection
@section("JS")
<?php
$PageHelper->addJS("DataTables");
$PageHelper->addJS("jszip");
$PageHelper->addJS("pdfmake");
?>
<script>
$(function(){
    let lessonN;
    let tableItem;
    let row;

    const mappingTable = new DataTable("#mappingTable",{
        language: { url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/uk.json'},
        ordering: false,
        responsive: true, lengthChange: true, autoWidth: false,
        columnDefs: [{targets: 0,visible: false,searchable: false}],
        ordering: false,
        paging: false,
        order: [[0, 'asc']]
        // iDisplayLength: -1, searching: false, paging: false, info: false
    })
    

    $('#modal-themes').on('show.bs.modal', function (event) {

        $(this).find('select').each(function() {
            var dropdownParent = $(document.body);
            if ($(this).parents('.modal.in:first').length !== 0)
            dropdownParent = $(this).parents('.modal.in:first');
            $(this).select2({
            dropdownParent: dropdownParent,
            theme: 'bootstrap4',
            templateSelection: formatThemesSelect
            });
        });

        var button = $(event.relatedTarget) // Button that triggered the modal
        lessonN = button.data('lesson') // Extract info from data-* attributes
        row = button.data('row') // Extract info from data-* attributes
        tableItem = $("#lesson" + lessonN)
        
        var modal = $(this)
        modal.find('.modal-title').text( "{{ __('Theme for lesson') }} " + lessonN)

        $("#lesson").val(lessonN)

    })
    
    document.querySelector('#saveData').addEventListener('click', function () {

        let select = $("#themesListSelect").select2("data");
        let type = select[0].title == "M" ? -1 : 1;

        $.ajax({
            url: "{{ route('curriculum.mapping.update.ajax',[$subjectId,$mappingId]) }}",
            type: "POST",
            data: {
                    "_token": <?= "'" . csrf_token() . "'" ?>,
                    "lesson": $("#lesson").val() * type,
                    "mappingId": {{$mappingId}},
                    "themeId": $("#themesListSelect").val()
                },
            success: function (data) {
                if(type==-1) { 
                    location.reload(true);
                }else{
                    mappingTable.cell(row,2).data(select[0].text);
                    mappingTable.cell(row,3).data("<span class='badge badge-danger'>{{ __('It is being edited') }}</span>");
                }
            }
        });
    });

    function formatThemesSelect (item) {

        var $item = $(
            '<span><span class="badge"></span> <strong></strong></span>'
        );

        var title = "";

        $item.find("strong").text(item.text);
        
        if (item.title=="M") {
            $item.find(".badge").text("{{ __('Module') }}").addClass("badge-primary");
        }else if(item.title=="T"){
            $item.find(".badge").text("{{ __('Theme') }}").addClass("badge-success");
        }
        
        // console.log(item);
        return $item;
    };

});
</script>
@endsection