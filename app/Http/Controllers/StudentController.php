<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Helpers\Handler;
use App\Models\Timetable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Handler $Handler)
    {
        return view("student.index",[
            "permission"=>true,
        ]);
    }

    public function ajax(Request $request, Student $student){
        return response()->json($student->getStudentsDataTable($request));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("student.create",[
            "permission"=>true,
        ]);
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
    public function show(Request $request, Student $student, Timetable $timetable, Handler $handler)
    {

        $data = $student
            ->with(["group.groupSettsUsers","grades.subjectShort"])
            ->where('id',"=",$request->student->id)
            ->get()
            ->first();

        $data->group->open = Carbon::parse($data->group->open);
        $data->group->close = Carbon::parse($data->group->close);
        
        $data->enrolled = empty($data->enrolled) ? $data->group->open : Carbon::parse($data->enrolled);
        $data->expelled = empty($data->expelled) ? $data->group->close : Carbon::parse($data->expelled);

        $data->group->groupSettsUsers = $data->group->groupSettsUsers->keyBy("id");

        $data->setRelation("grades", $handler->studentSubejctHoursGradesTotalArr($data->grades));

        $subjectHours = $timetable
        ->with("subject")
        ->selectRaw("subject_id, count(*) AS hours")
        ->where('group_id', $data->group_id)
        ->whereBetween('begin', [$data->enrolled, $data->expelled])
        ->groupBy("subject_id")
        ->get();

        // dd($data->toArray(),$subjectHours->toArray());

        return view("student.show",[
            "data"=>$data,
            "subjectHours"=>$subjectHours,
            "permnission"=> User::hasRoleName("admin"),
            "pageTitle"=>implode(" ", [$data->last_name, $data->first_name, $data->middle_name ]),
            "breadcrumbs"=>[
                    ['name'=>'Edit','current'=>true],
                ]
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }
}
