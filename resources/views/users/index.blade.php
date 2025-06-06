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
            <li class="breadcrumb-item"><a href="#">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Users') }}</li>
          </ol>
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>
    <!-- Main content -->
    <section class="content">
        <div class="card card-solid">
            <div class="card-body pb-0">
                <div class="row">
                    @foreach ($usersList as $user)
                    <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                        <div class="card bg-light d-flex flex-fill">
                            <div class="card-header text-muted border-bottom-0">
                                {{-- {{ __($user->role) }} --}}
                            </div>
                            <div class="card-body pt-0">
                                <div class="row">
                                    <div class="col-7">
                                        <h2 class="lead"><b>{{ $user->last_name . ' ' . $user->first_name }}</b></h2>
                                        {{-- <p class="text-muted text-sm"><b>{{ __("Duty") }}: </b> {{ __($user->sub_role) }} </p> --}}
                                        <ul class="ml-4 mb-0 fa-ul text-muted">
                                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-at"></i></span> {{__("Email")}}: {{ $user->email }}</li>
                                            <li class="small"><span class="fa-li"><i class="fas fa-lg fa-phone"></i></span> {{__("Phone")}}: </li>
                                        </ul>
                                    </div>
                                    <div class="col-5 text-center">
                                        <img src="{{ $PageHelper->getUserAvatarPath($user->id) }}" alt="user-avatar" class="img-circle img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="text-right">
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-user"></i> {{__("Profile")}}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer">
                {{ $usersList->links() }}
              </div>
        </div>
  
    </section>
    <!-- /.content -->
@endsection