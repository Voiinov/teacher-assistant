@section('content')
    @foreach ($data as $course=>$groups)
    <h5 class="mb-2 description-block">{{$course}} <small class='description-text'>{{__("Study year")}}</small></h5>
    <div class="row">
        @foreach ($groups as $groupId => $group)
            <div class="col-md-6">
                <div class="card card-widget widget-user shadow-lg">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    @isset($group->groupSettsProfessions[0]->design['c'])
                        <div class="widget-user-header text-white" style="background: url('{{ Storage::url("img/covers/" . $group->groupSettsProfessions[0]->design['c'] . ".jpg") }}') center center;background-size:cover">
                    @else
                        <div class="widget-user-header text-white bg-warning" style="background: url('../dist/img/photo1.png') center center;">
                    @endisset
                    <h3 class="widget-user-username text-right">{{ $group->groupSettsProfessions->implode("name","; ") }}</h3>
                    <h5 class="widget-user-desc text-right">{{ $group->groupSettsProfessions->implode("kp","; ") }}</h5>
                    </div>
                    <div class="card-body p-0">
                        @isset($buttons)
                            <div class="btn-group btn-block">
                                @foreach ($buttons as $page=>$options)
                                    <a href="{{ route("public", ["page"=>$page,"group"=>$groupId])}}" type="button" class="btn btn-primary btn-flat"><i class="{{$options['icon']}}"></i> {{ $options["title"] }}</a>    
                                @endforeach
                            </div>
                        {{-- <a href="{{ route("public", ["page"=>"timetable","group"=>$groupId])}}" type="button" class="btn btn-primary btn-flat btn-block"><i class="fa fa-bell"></i> {{__("Timetable")}}</a> --}}
                        @endisset
                    </div>
                    <div class="card-footer pt-0">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                        <div class="description-block">
                            <h5 class="description-header">{{ $group['groupIndex'] }}</h5>
                            <span class="description-text">{{ __("Group") }}</span>
                        </div>
                        <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 border-right">
                        <div class="description-block">
                            <h5 class="description-header">{{ $course }}</h5>
                            <span class="description-text">{{__("Course")}}</span>
                        </div>
                        <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4">
                        <div class="description-block">
                            <h5 class="description-header">{{ $group->open->diff($group->close) }}</h5>
                            <span class="description-text">{{ __("Study period") }}</span>
                        </div>
                        <!-- /.description-block -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endforeach
@endsection