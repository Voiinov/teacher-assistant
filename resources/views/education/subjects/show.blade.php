@extends('layouts.dashboard')
@section("pageTitle")
    {{ __($pageTitle) . " | " }}
@endsection
@section("CSS")

@endsection
@section('content-header')
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>{{ __($pageTitle) }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ url('/home') }}">{{ __('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('subject.index') }}">{{ __('Subjects') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('Subjects') }}</li>
                </ol>
            </div>
        </div>
@endsection
@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
              <div class="row">
                <div class="col-12">
                  <h4>{{ __('Events') }}</h4>
                  @foreach ($events['data'] as $event)
                    <div class="post clearfix">
                      <div class="user-block">
                        <img class="img-circle img-bordered-sm" src="{{ $PageHelper->getUserAvatarPath($event['user_id']) }}" alt="{{ $event['name'] }}">
                        <span class="username">
                          <a href="{{ route('users.show',$event['user_id']) }}"><?= $event['last_name'] . " " . $event['first_name'] ?></a>
                        </span>
                        <span class="description">{{ __($event['action']) }} - {{ $PageHelper->eventDate($event['created_at']) }}</span>
                      </div>
                      <!-- /.user-block -->
                      <p>
                        @isset ($event['themeId'])
                        {{ Str::words(__($event['description'],["theme"=>$events['themes'][$event['themeId']] ?? ""]),35) }}                          
                        @endif
                      </p>
                      @isset($event['link'])
                      <p>
                        <a href="{{$event['link']}}" class="link-black text-sm"><i class="fas fa-info-circle mr-1"></i> {{ __("Details") }}</a>
                      </p>
                      @endisset                    
                    </div>
                  @endforeach
                </div>
              </div>
            </div>
            <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
              <h3 class="text-primary"><i class="{{ $options['fa_icon'] }}"></i> {{ __("Description") }}</h3>
              <p class="text-muted">{{ __($options['description']) }}</p>
              <br>
              <div class="text-muted">
                <p class="text-sm">{{ __("They teach") }}
                  <b class="d-block"></b>
                </p>
                <p class="text-sm">{{ __("They can teach") }}
                  <b class="d-block"></b>
                </p>
              </div>

              <h5 class="mt-5 text-muted">{{ __('Attacments') }}</h5>
              <ul class="list-unstyled">
                <li>
                  <a href="https://mon.gov.ua/" class="btn-link text-secondary"><i class="fa fa-link"></i> Офіційний сайт Міністерства освіти і науки України</a>
                </li>
              </ul>
              <div class="mt-5 mb-3">
                <a href="{{ route("curriculum.themes.index", $subjectId) }}" class="btn btn-app bg-info">
                  <span class="badge bg-warning">{{ $counter['themes'] }}</span>
                  <i class="fas fa-barcode"></i> {{ __("Themes") }}
                </a>
                <a href="{{ route("curriculum.themes.index", $subjectId) }}" class="btn btn-app bg-danger disabled">
                  <span class="badge bg-primary">{{ $counter['curriculums'] }}</span>
                  <i class="fas fa-barcode"></i> {{ __("Сurriculums") }}
                </a>
                <a href="{{ route("curriculum.mapping.index", $subjectId) }}" class="btn btn-app bg-secondary">
                  <span class="badge bg-success">{{ $counter['mappings'] }}</span>
                  <i class="fas fa-barcode"></i> {{ __("Curriculum mappings") }}
                </a>
              </div>
            </div>
          </div>    
    </div>
</div>
@endsection
@section("JS")

@endsection