<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\AppOptions;
use App\Models\Groups;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;


class PublicController extends Controller
{
    public function index(Request $request, AppOptions $appOptions, Groups $groups)
    {

        $page = $request->page ?? "index";
        $date = $this->getDate($request);

        switch($page){
            case("timetable"):
                if(isset($request->group))
                {
                    $viewData = $this->caseTimetable($date,$request->group);
                    $viewData["pageTitle"] = __("Timetable on :date",["date"=>$date->format("d.m.Y")]);
                    $viewData["page"] = "public.{$page}.show";
                    $viewData["groupData"]=$groups->getGroupData($request->group);
                    
                }else{
                    $viewData = [
                        "page" => "public.{$page}.index",
                        "data" => $groups->getActiveGroups(),
                        "buttons"=>[
                            "calendar"=>[
                                "title"=>__("Calendar"),
                                "icon"=>"fa fa-calendar"
                                ]
                            ],
                    ];
                    if(Auth::check()){
                        $viewData["buttons"]["timetable"]=[
                            "title"=>__("Timetable"),
                            "icon"=>"fa fa-bell"
                        ];
                    }
                }
            break;
            case("calendar"):
                $viewData = $this->caseCalendar($request->group);
                $viewData["groupData"]=$groups->getGroupData($request->group);
                $viewData['pageTitle'] = __("Group calendar") . " " . $viewData["groupData"]->groupIndex;
                $viewData['page'] = "public.{$page}.show";
                break;
            default:
        }

        $viewData["appOptions"]=$appOptions;
        $viewData["date"]=$date;

        // dd($viewData["groupData"]->toArray());

        return view("public",$viewData);
    }

    private function caseTimetable($date, $groupId){
        $data = $this->timetable($date->copy()->startOfDay(),$date->copy()->endOfDay(),$groupId);
        return [
            "data"=>
                $data->map(function($lesson){
                    $lesson->begin = Carbon::parse($lesson->begin);
                    $lesson->end = Carbon::parse($lesson->end);
                    return $lesson;
            }),
        ];
    }

    private function caseCalendar($groupId){
        return [
            "data" => [
                "googleCalendarApiKey"=>"AIzaSyBWD2iWX-2gCbE7-wy8ZvAPhhJeMJ7NOqs",
                "workTime"=>["startTime"=>"8:00","endTime"=>"17:00"],
                "route"=>
                    route("api",[
                        "get"=>"calendar",
                        "groupId"=>$groupId,
                        "key"=>md5('24041985'),
                    ]
                ),
            ],
        ];
    }

    private function timetable($begin, $end, $groupId)
    {
        $timetable = new \App\Models\Timetable();

        return $timetable
            ->with(["subject", "user"])
            ->where("begin","<",$end)
            ->where("end",">",$begin)
            ->where("group_id","=", $groupId)
            ->orderBy("begin")
            ->get();
    }

    private function getDate(Request $request)
    {
        if(isset($request->date)){
            $date = Carbon::parse($request->date);
        }else{
            $date = Carbon::now()->hour >= 18 ? Carbon::tomorrow() : Carbon::now();
        }

        return $date;
    }

}
