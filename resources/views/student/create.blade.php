@extends('layouts.dashboard')
@section("CSS")
@endsection
@section('content-header')
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ __('Education seeker') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.index') }}"> {{ __('Learners') }}</a></li>
                    <li class="breadcrumb-item active">{{ __("Create") }}</li>
                </ol>
            </div>
        </div>
@endsection
@section('content')
<form action="" method="post">
<div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __("Create") }}</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <?php
                foreach([
                    'last_name'=>["Last name","text"],
                    'first_name'=>["Name", "text"],
                    'middle_name'=>["Middle name","text"],
                    ] 
                    as $key=>$data):
                    ?>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ __($data[0]) }}</label>
                        <input type="<?= $data[1] ?>" name="<?= $key ?>" class="form-control" placeholder="<?= __($data[0]) ?>">
                    </div>
                </div>
                <?php endforeach ?>
                <div class="col-md-4">
                <div class="form-group">
                    <label for="">{{ __("Birthday") }}</label>
                    <input type="text" class="form-control datemask" data-inputmask-alias="datetime" data-inputmask-inputformat="dd.mm.yyyy" data-mask="" inputmode="numeric" placeholder="dd.mm.yyyy">
                </div>
                </div>
                <div class="col-md-4">
                <div class="form-group">
                    <label for="">РНОКПП</label>
                    <input type="text" id="code" class="form-control" placeholder="00000 000 0 0">
                </div>
                </div>
            </div>
        </div>
    </div>
</form>
    
@endsection
@section("JS")
<?php 
$PageHelper->addJS('inputmask');
?>
<script>
$(function () {
    $('#code').inputmask('99999 999 9 9', { 'placeholder': '00000 000 0 0', 'max':10 });
    $('[data-mask]').inputmask();
});
</script>
@endsection