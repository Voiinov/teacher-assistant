@extends('layouts.dashboard')
@section("pageTitle")
    {{ __($pageTitle) }}
@endsection
@section("CSS")
    {{ $PageHelper->addCSS("DataTables") }}
@endsection
@section('content-header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1>{{ __('Subjects') }}</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url('/home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Subjects') }}</li>
        </ol>
    </div>
</div>
@endsection
@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __('Subjects list') }}</h3>
        <div class="card-tools">
            <a class="btn btn-tool" href="{{ route('subject.create') }}"><i class="fa fa-plus"></i></a>
        </div>
    </div>
    <div class="card-body">
        <table id="subjects" class="table">
            <thead>
                <tr>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Short title') }}/{{ __("Abbreviation") }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subjects as $subject)
                    <tr>
                        <td>{{ __($subject->status) }}</td>
                        <td>{{ __($subject->type_short) }}</td>
                        <td>{{ $subject->title }}</td>
                        <td>{{ $subject->short_title }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('subject.show', $subject->id) }}" class="btn btn-primary btn-xs btn-flat"><i class="fas fa-info-circle"></i> {{ __("Details") }}</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
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
      var table = $("#subjects").DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/uk.json',
        },
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": [
            { extend:"excel", text:'<i class="fa fa-file-excel"></i>', className:"btn-tool" },
            { extend:"pdf", text:'<i class="fa fa-file-pdf"></i>', className:"btn-tool" },
            { extend:"print", text:'<i class="fa fa-print"></i>', className:"btn-tool" }
        ],
        initComplete: function () {
            table.buttons().container().appendTo('.card-tools:eq(0)'),
            $(".card-tools .btn-tool").removeClass('btn-secondary')
        }
      });
    });
  </script>
@endsection