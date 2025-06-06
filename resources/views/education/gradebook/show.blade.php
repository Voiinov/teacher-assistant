@extends('layouts.dashboard')
@section("pageTitle")
    {{ $pageTitle }}
@endsection
@section("CSS")
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/5.0.3/css/fixedColumns.bootstrap.css">
<style>
.dt-layout-cell{
    padding: 0px !important;
}

input[type=text]{
    text-align: center;
    text-transform: uppercase;
}

#gradebook tbody tr td:not(:first-child):not(:last-child) {
    padding:0px;
    vertical-align: middle;
}

#gradebook tbody tr td:is(:last-child) {
    vertical-align: middle;
    text-align: center;
}

</style>
@endsection
@section('content-header')
    <x-page.header :$breadcrumbs>
        {{ __($pageTitle) }}
    </x-page.header>
@endsection
@section('content')

    @empty($grades)
        @include("education.gradebook.content.list")
    @else
        @include("education.gradebook.content.table")
    @endempty


    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ __("Legend") }}</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-sm-4 col-md-2">
                    <div class="color-palette-set">
                        <span class='bg-warning color-palette pr-4 mr-2'></span> {{__("Expelled")}}
                    </div>
                </div>
                <div class="col-sm-4 col-md-2">
                    <div class="color-palette-set">
                        <span class='bg-success color-palette pr-4 mr-2'></span> {{__("Not enrolled")}}
                    </div>
                </div>
                <div class="col-sm-4 col-md-2">
                    <div class="color-palette-set">
                        <span class='bg-gray color-palette pr-4 mr-2'></span> {{__("The grade was not set by you")}}
                    </div>
                </div>
            </div>
        </div>    
    </div>

@endsection
@section("JS")

<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/5.0.3/js/dataTables.fixedColumns.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/5.0.3/js/fixedColumns.bootstrap.js"></script>
<script src="{{ asset("plugins/inputmask/jquery.inputmask.min.js") }}"></script>

<script>


$(function() {

    let gradebook = new DataTable('#gradebook', {
        ordering: false,
        language: { url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/uk.json' },
        fixedColumns: {start:1, end:1},
        paging: false,
        scrollCollapse: true,
        scrollX: true,
        scrollY: 500
    });

    // $('.grade').inputmask({
    //     mask: ["","-","H","9","1[0][1][2]"],
    //     greedy: false,
    //     placeholder: "",
    //     definitions: {
    //         "H": { validator: "[нH]", casing: "upper" },
    //         "-": { validator: "[-]", casing: "upper" },
    //         "": { validator: "[]"},
    //     }
    // });
/*
    gradebook.on('click', 'tbody td', function () {
        $('#set-grades').modal('toggle');
    });
*/
    $('#vert-tabs-tab > a').on('click', function () {

        $(".overlay").toggle();
        getGrades($(this).data('subject-id'),gradebook);

    });

});

</script>
@endsection