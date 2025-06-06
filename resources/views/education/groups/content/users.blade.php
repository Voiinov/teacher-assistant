<div class="card">
    <div class="card-body">
        <div id="accordion">
            @foreach ($data as $year=>$subjects)
                <div class="card">
                    <div class="card-header">
                    <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseYear{{$year}}">
                            {{ __("Year") }}: {{$year}}
                        </a>
                    </h4>
                    </div>
                    <div id="collapseYear{{$year}}" class="collapse" data-parent="#accordion">
                    <div class="card-body p-0">
                        <table class="table table-hover projects">
                            @foreach ($subjects as $subjectId => $subject)
                            {{-- {{dd($subject)}} --}}
                                <tr>
                                    <td>{{ $subject['title'] }}</td>
                                    <td class='text-center'>
                                        <ul class="list-inline">
                                            @foreach($subject['users'] as $user)
                                                @isset($user->id)
                                                    <li class='list-inline-item'>
                                                        <a href="{{route("users.show",$user->id)}}"><img src="/storage/img/avatars/20240816_175601.jpg" class='table-avatar' alt="{{ $user->name ?? "" }}"></a>
                                                        {{-- <a class="users-list-name" href="#">{{ $user->name ?? "" }}</a> --}}
                                                    </li>
                                                @endisset
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                  </div>
                </div>
            @endforeach
        </div>
    </div>
</div>