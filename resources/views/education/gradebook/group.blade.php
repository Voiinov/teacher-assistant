@extends('layouts.dashboard')
@section("pageTitle")
    {{ __($pageTitle) }}
@endsection
@section("CSS")

@endsection
@section('content-header')
    <x-page.header :$breadcrumbs>
        {{ __($pageTitle) }}
    </x-page.header>
@endsection
@section('content')    
<div class="card card-solid">
    <div class="card-body pb-0">
        <div class="row">
            @isset($list)
                @foreach ($list as $gradeBook)
                    <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                        <div class="card bg-light d-flex flex-fill">
                            @isset($gradeBook['close_year'])
                                <x-elements.ribbon color="danger" xl>{{ __("Closed") }}</x-elements.ribbon>
                            @else
                            @isset($gradeBook['close_semestr'])
                                <x-elements.ribbon color="danger" lg>{{ __("Closed") }}</x-elements.ribbon>
                            @endisset
                            @endisset
                            <div class="card-header text-muted border-bottom-0">
                                {{ __(":yearst year of study", ['year'=>$gradeBook['study_year']]) }}
                            </div>
                            <div class="card-body pt-0">
                              <div class="row">
                                  <h2 class="lead"><b>{{ __("Grade book of group :group",["group"=>$gradeBook['group_index']]) }}</b></h2>
                                </div>
                            </div>
                            <div class="card-footer">
                              <div class="text-right">
                                <a href="#" class="btn btn-sm bg-teal disabled">
                                  <i class="fas fa-print"></i>
                                </a>
                                <a href="{{ route("gradebook.show", $gradeBook['id']) }}" class="btn btn-sm btn-primary">
                                  <i class="fas fa-book"></i> {{ __('View') }}
                                </a>
                              </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endisset
            <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column">
                <div class="card bg-light d-flex flex-fill">
                    <a class="btn btn-default" href="{{ route("gradebook.create",["group" => $groupID]) }}">
                        <div class="card-body p-0 align-content-center text-center" style="height: 250px">
                            <i class="fa fa-plus fa-3x"></i>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section("JS")

@endsection