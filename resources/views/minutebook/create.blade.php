@extends('layouts.dashboard')
@section("CSS")
<?php 
$PageHelper->addCSS('select');
$PageHelper->addCSS('summernote')
 ?>
@endsection
@section('content-header')
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ __('Student') }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('minutebook.index') }}"> {{ __('Minute book') }}</a></li>
                    <li class="breadcrumb-item active">{{ __("Create") }}</li>
                </ol>
            </div>
        </div>
@endsection
@section('content')
<form action="{{ route('minutebook.store') }}" method="post">
    @csrf
    <div class="card">
    <div class="card-header">
        <h3 class="card-title">{{ __("Create document") }}</h3>
    </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>{{ __('Document date') }}</label>
                        <input type="text" name="doc_date" class="form-control datemask @error('doc_date') is-invalid @enderror" data-inputmask-alias="datetime" data-inputmask-inputformat="dd.mm.yyyy" data-mask="" inputmode="numeric" placeholder="dd.mm.yyyy" value="{{ old('doc_date') }}" required>
                        @error('doc_date')<span class="error invalid-feedback">{{ __($errors->first('doc_date') ) }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>{{ __('Number') }}</label>
                        <input type="text" name="number" class="form-control @error('number') is-invalid @enderror" value="{{ old('number') }}" required>
                        @error('number')<span class="error invalid-feedback">{{ __($errors->first('number') ) }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">{{ __("Document") }}</label>
                        <select name="doc_type" class="form-control select2" style="width: 100%;">
                            @foreach ( $doc_types as $item )
                                <option value="{{ $item->id }}">{{ $item->name }}</option>    
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="">{{ __('Title') }}</label>
                        <input name="title" type="text" class="form-control @error('number') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')<span class="error invalid-feedback">{{ __($errors->first('title') ) }}</span>@enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <label for="">{{ __('Description') }}</label>
                    <textarea name="description" id="summernote"></textarea>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-info">{{ __('Create') }}</button>
            <a href="{{ route("minutebook.index") }}" class="btn btn-default float-right">Cancel</a>
        </div>
    </div>
</form>
    
@endsection
@section("JS")
    <?php 
    $PageHelper->addJS('inputmask');
    $PageHelper->addJS('select');
    $PageHelper->addJS('summernote');
    ?>
    <script>
    $(function () {
        $('#code').inputmask('99999 999 9 9', { 'placeholder': '00000 000 0 0', 'max':10 });
        $('[data-mask]').inputmask();
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
        theme: 'bootstrap4'
        })
         // Summernote
        $('#summernote').summernote()
    });
    </script>
@endsection