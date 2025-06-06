@extends('layouts.dashboard')

@section('content')

   <!-- Content Header (Page header) -->
   <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>{{ __('Users') }}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url("/dashboard") }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('Users') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.show', $user) }}">{{ __('Profile') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Edit') }}</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
    <!-- Main content -->
    <section class="content">
        
        <form action="{{ route('users.update', $user) }}" method="post">
            @csrf
            @method('PUT')
            <div class="card card-solid">
                <div class="card-body pb-0">
                    <div class="form-group">
                        <label for="last_name">{{ __("Last name") }}</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" placeholder="{{ __('Enter last name') }}" value="{{ $user->last_name }}">
                      </div>
                    <div class="form-group">
                        <label for="first_name">{{ __("Name") }}</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="{{ __('Enter name') }}" value="{{ $user->first_name }}">
                      </div>
                    <div class="form-group">
                        <label for="second_name">{{ __("Middle name") }}</label>
                        <input type="text" class="form-control" id="middle_name" name="middle_name" placeholder="{{ __('Middle name') }}" value="{{ $user->middle_name }}">
                      </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('users.index') }}" class="btn btn-default">{{ __('Cancel') }}</a>
                    <button type="submit" class="btn btn-primary">{{ __("Submit") }}</button>
                  </div>
            </div>
        </form>
    </section>
    <!-- /.content -->
@endsection