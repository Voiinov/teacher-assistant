@extends('layouts.dashboard')

@section('content')

   <!-- Content Header (Page header) -->
   <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>{{ __('Profile') }}</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ url("/dashboard") }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">{{ __('Users') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Profile') }}</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
  <section class="content">
        <div class="container-fluid">
            <div class="row">
                @session('message')
                <div class="success">{{ session('message') }}</div>                    
                @endsession
                <div class="col-md-3">
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                          <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="{{ $PageHelper->getUserAvatarPath($user->id) }}" alt="User profile picture">
                          </div>
          
                          <h3 class="profile-username text-center">{{ $user->last_name . " " . $user->first_name }}</h3>
          
                          <p class="text-muted text-center">{{ $user->user_role }}</p>
                        </div>
                        <!-- /.card-body -->
                      </div>
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">

                        </div>
                        <div class="card-body">

                        </div>
                    </div>
                </div>
            </div>
        </div>
  </section>
@endsection