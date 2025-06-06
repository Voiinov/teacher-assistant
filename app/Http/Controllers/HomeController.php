<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use League\OAuth2\Client\Provider\Google;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Timetable;
use Carbon\Carbon;
use App\Helpers\AppOptions;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request, AppOptions $appOptions)
    {   
        $subjects = Timetable::with(["subject","group", "gradeBookActualOnly"])
                ->where("user_id", "=", Auth::id())
                ->where("begin", ">=", $appOptions->studyBegin())
                ->where("end", ">=", $appOptions->studyBegin())
                ->select("subject_id", "group_id")
                ->distinct()
                ->get();
        
        $subjects->each(function($item){
            $item->group->open = Carbon::parse($item->group->open);
            $item->group->close = Carbon::parse($item->group->open);
            $item->group->study = $item->group->open->diff(Carbon::now());
            $item->group->index = sprintf($item->group->mask,$item->group->study->year+1);
            return $item;
        });

        return view('home',[
            "data"=>["googleCalendarApiKey"=>"AIzaSyBWD2iWX-2gCbE7-wy8ZvAPhhJeMJ7NOqs",],
            "options"=>$appOptions,
            "subjects"=>$subjects,
        ]);

    }
}
