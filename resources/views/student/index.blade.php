@extends('layouts.dashboard')
@section("CSS")
    <?php $PageHelper->addCSS("DataTables") ?>
@endsection
@section('content-header')
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ __('Education seekers') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
                    <li class="breadcrumb-item active">{{ __('Learners') }}</li>
                </ol>
            </div>
        </div>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('Learners list') }}</h3>
        <div class="card-tools">
            <a class="btn btn-tool" href="{{ route('student.create') }}"><i class="fa fa-plus"></i></a>
        </div>
    </div>
    <div class="card-body">
        @csrf
        <table id="students" class="table">
            <thead>
                <tr>
                    <th>{{ __('Last name') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Middle name') }}</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection
@section("JS")
<?php
$PageHelper->addJS("DataTables");
$PageHelper->addJS("jszip");
$PageHelper->addJS("pdfmake");

?>
<script>
    $(function () {

      var table = $("#students").DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/uk.json',
        },
        ajax: {
            url: <?= "'" . route("students.datatable") . "'" ?>,
            type: 'POST',
            data: function(d) {
                    d._token = <?= "'" . csrf_token() . "'" ?>;
                    // Get current page information from DataTables API
                    d.page = table.page.info().page+1;
                    return d;
                }
        },
        columns: [
            { data: 'last_name' },
            { data: 'first_name' },
            { data: 'middle_name' }
        ],
        processing: true,
        serverSide: true,
        "responsive": true, "lengthChange": true, "autoWidth": false,
        "buttons": [
            { extend:"excel", text:'<i class="fa fa-file-excel"></i>', className:"btn-tool" },
            { extend:"pdf", text:'<i class="fa fa-file-pdf"></i>', className:"btn-tool" },
            { extend:"print", text:'<i class="fa fa-print"></i>', className:"btn-tool" }
        ],
        initComplete: function () {
            table.buttons().container().appendTo('.card-tools:eq(0)'),
            $(".card-tools .btn-tool").removeClass('btn-secondary')
            // console.log(table.page.info().page)
        }
      })
    });
  </script>
@endsection