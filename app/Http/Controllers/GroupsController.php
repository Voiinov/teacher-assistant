<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Groups;
use App\Models\Timetable;
use Illuminate\Support\Carbon;
use App\Models\Profession;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Groups $groups)
    {
        return view("education.groups.index", [
            "pageTitle" => __("Groups"),
            "cardTitle" => __("Groups list"),
            "breadcrumb" => [
                ['name' => 'Groups', 'current' => true],
            ],
            "groupsList" => $groups->getActiveGroups(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Groups $groups, Timetable $timetable)
    {
        $data = [];
        $page = $request->page ?? "dafault";

        if($page == "students"){
            $data = $groups->with("students")->where("id","=", $request->group);
            if(!$data->exists()){ return back(); }
            $data = $this->studetnsList($data);
        }

        if($page == "users"){
            $data = $timetable->getGroupYearSubjectUsers($request->group);
            if(!$data->exists()){ return back(); }
            $data = $groups->arrayByYearSubjectUsersForGroup($data->get()->groupBy("year"));
        }

        return view("education.groups.show",[
            "pageTitle"=>"Group",
            "page"=>$page,
            "data"=>$data,
        ]);
    }


    private function studetnsList($data)
    {
        $data = $data->first();

        $data->open = Carbon::parse($data->open);
        $data->close = Carbon::parse($data->close);

        $data->setRelation("students", 
            $data->students->map(function($student) use($data)
            {

                $student->birthday = Carbon::parse($student->birthday);

                $student->enrolled = is_null($student->enrolled) ? $data->open : Carbon::parse($student->enrolled);
                $student->expelled = is_null($student->expelled) ? $data->close : Carbon::parse($student->expelled);

                return $student;
            }
        ));

        return $data;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Groups $groups, Profession $profession)
    {

        $data = $groups->getGroupSetts($request->group);
        
        if($data){
            $data->open = Carbon::parse($data->open);
            $data->close = Carbon::parse($data->close);
            $data->indx = 0;//sprintf($data->mask,[__("Study year")]);
            $data->groupSettsProfessions->map(function($prof) use($profession){
                $prof->design = $profession->getProfessionDesign($prof->design);
                return $prof;
            });

            $now = Carbon::now();
            $data->setRelation("studentsCounter", collect(["en"=>0,"ex"=>0,"active"=>0]));
            
            $data->students->each(function($student) use($data, $now){
                $counter = $data->getRelation("studentsCounter");

                $student->enrolled = Carbon::parse($student->enrolled);
                $student->expelled = Carbon::parse($student->expelled);
                
                if ($student->enrolled->greaterThanOrEqualTo($now)) {
                    $student->inList = false;
                    $counter->put("en", $counter->get("en") + 1);
                } elseif ($student->expelled->lessThan($now)) {
                    $student->inList = false;
                    $counter->put("ex", $counter->get("ex") + 1);
                } else {
                    $student->inList = true;
                    $counter->put("active", $counter->get("active") + 1);
                }
            });

        }else{
            return back();
        }

        $data->now = $now;
        // dd($data->toArray());
        return view("education.groups.edit",[
            "pageTitle"=>"Group",
            "data"=>$data
        ]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


}
