@extends('layouts.dashboard')
@section("pageTitle")
    {{ __($pageTitle) }}
@endsection
@section("CSS")
    <?php $PageHelper->addCSS("DataTables") ?>
@endsection
@section('content-header')
    <x-page.header :breadcrumbs="$breadcrumb">
        {{ __($pageTitle) }}
    </x-page.header>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('Timetable') }}</h3>
        <div class="card-tools"></div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                  <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">{{ __("Rows") }}</span>
                    <span class="info-box-number success">0</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                  <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-exclamation-triangle"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">{{ __("Errors") }}</span>
                    <span class="info-box-number errors">0</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <div class="col-12 col-sm-6 col-md-6" id="errorsLog">

            </div>
        </div>
        <table id="importDataTable" class="table table-hover">
            <thead>
                <tr>
                    @foreach (["Date"=>"","Group"=>"","Subject"=>"","Educator"=>"","Begin"=>"","End"=>"","Lesson"=>"","Row"=>"",""=>"style=width:50px"] as $col => $attr)
                    <th {{ $attr }}>{{ __($col) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tfoot>
                @foreach (["Date","Group","Subject","Educator","Begin","End","Lesson","Row",""] as $col)
                <th>{{ __($col) }}</th>
                @endforeach
            </tfoot>
        </table>
    </div>
    <div class="overlay"><i class="fas fa-2x fa-sync fa-spin"></i></div>
    <div class="card-footer justify-content-between">
        <x-button.default id="getData"><i class="fas fa-sync"></i> {{ __('Refresh') }} </x-button.default>
        <x-button.primary id="storeData" class='float-right'><i class="fas fa-download"></i> {{ __('Store') }} </x-button.primary>
    </div>
</div>
@endsection
@push("js")
<?php
$PageHelper->addJS("DataTables");
$PageHelper->addJS("jszip");
$PageHelper->addJS("pdfmake");
?>
<script>
    let err=0;
    const importDataTable = new DataTable("#importDataTable",{
        language: { url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/uk.json' },
        ajax:{
            url:"{{ route('timetable.import.ajax') }}",
            type: "GET",
            data: {
                    "_token": <?= "'" . csrf_token() . "'" ?>
                }
        },
        columnDefs:[
            {
                targets: '_all',
                render: function (data, type, row) {
                    return typeof data === 'object' && data !== null ? data.display : data;
                },
                createdCell: function (td, cellData, rowData, row, col) {
                    
                    // Перевіряємо, чи є cellData об'єктом і не є null
                    if (typeof cellData === 'object' && cellData !== null) {                        
                        $(td).data('display', cellData.display)  // Додаємо атрибут data-display
                            //  .attr('sort', cellData.sort)      // Додаємо атрибут data-sort
                            // //  .attr('db', cellData.db) 
                            //  .attr('type', cellData.type)
                            //  .attr('name', cellData.name)

                        if(cellData.error === "error"){
                            $(td).attr('class','text-danger importErr');
                        }

                    }
                }
            }
        ],
        action: function ( e, dt, button, config ) {
            $.ajax({
                type: "POST",
                data:{'year':2021},
                url: "{{ route('timetable.import.store.ajax') }}",
            });
        },
        initComplete: function () {
            $(".success").html(this.api().rows().count());
            errorsCount = this.api().rows().cells('.importErr').count()
            $(".errors").html(errorsCount);
            if(errorsCount>0)
                $("#storeData").addClass("disabled");
            else
                $("#storeData").removeClass("disabled");
            
            $(".overlay").toggle();
        },
        "responsive": true, "lengthChange": true, "autoWidth": false,
        // columnDefs: [{targets: 0,visible: false,searchable: false}]
    })

    document.querySelector('#storeData').addEventListener('click', function () {
        $.ajax({
            url: "{{ route('timetable.import.store.ajax') }}",
            type: "POST",
            data: {
                "_token": <?= "'" . csrf_token() . "'" ?>,
                "data": importDataTable.rows().data().toArray(),
                "success": function (data, status, xhr) {
                        console.log(data);
                },
                "error": function (xhr, error, thrown) {
                    console.log('Error in Operation');
                }
            }
        })
    });

    document.querySelector('#getData').addEventListener('click', function () {
        $(".overlay").toggle();
        importDataTable.ajax.reload(function(){
            $(".overlay").toggle();
        });
    });
</script>
@endpush
