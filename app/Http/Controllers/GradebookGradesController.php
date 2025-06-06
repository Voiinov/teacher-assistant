<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gradebook;
use App\Models\GradebookGrades;
use App\Models\Groups;
use App\Models\Timetable;
use Illuminate\Support\Facades\Auth;
use App\Models\Options;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class GradebookGradesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Groups $groups)
    {

        $timeTable = new Timetable();

        $data = $timeTable
            ->with(["grades","gradeBooks"])
            ->where("id","=",$request->lessonID)->first();

        if(isset($data)){

            $data->setRelation("grades", $data->grades->keyBy("student_id"));

            $data->setRelation(
                "gradeBooks", 
                $data->gradeBooks->keyBy("open")
            );

            $data->begin = Carbon::parse($data->begin);
            $data->end = Carbon::parse($data->end);

            $data->group->setRelation('students', $data->group->students->keyBy("id")->map(function($student) use($data){
                $student->setRelation("labels",collect());
                return $this->parseStudentData($student, $data);
            }));

            $studyBegin = (new Options)->calculateStudyBegin();
            
            $lessonNumber = $timeTable
                    // ->select("COUNT('id')")
                    ->whereBetween("begin",[$studyBegin,$data->end])
                    ->where("subject_id","=",$data->subject_id)
                    ->where("group_id","=",$data->group_id)
                    ->count();

        }

        $subject = $data->subject->isSubgroup->title ?? $data->subject->title;
        return view("education.gradebook.grade",[
            "pageTitle"     => $subject,
            "headerTitle"   => $subject,
            "headerSubTitle" => isset($data->subject->isSubgroup->id) ? $data->subject->title : null,
            "breadcrumbs"   =>[
                ['name'=>__("Journal"),"current"=>true]
            ],
            "data"          =>$data,
            "gradeBook"     => isset($data->gradeBooks[$studyBegin->format("Y-m-d")]->id) ? $data->gradeBooks[$studyBegin->format("Y-m-d")] : false,
            "lessonId"      => $request->lessonID,
            "lessonN"      => (int)substr($request->lessonID,10,2),
            "lessonCounter"      => $lessonNumber,
            "lessonTime"   => $data->begin->diffInMinutes($data->end),
            "gradesNet"     => [
                    "grades"=>[
                        1=>null,2=>null,3=>null,
                        4=>null,5=>null,6=>null,
                        7=>null,8=>null,9=>null,
                        10=>null,11=>null,12=>null
                    ],
                    "byGroups"=>[
                        "Initial"=>[1,2,3],
                        "Intermediate"=>[4,5,6],
                        "Sufficient"=>[7,8,9],
                        "High"=>[10,11,12]
                    ],
                ],
            "group" => $groups->getGroupIndex($data->group->mask, $data->group->open, $data->begin)
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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

    public function groupSubjectGradesAjax(Request $request, Gradebook $gradebook)
    {
        // dd($gradebook->subjectGradesByStudentArr($request));
        return response()->json($gradebook->subjectGradesByStudentArr($request));
    }

    private function parseStudentData($student, $data)
    {
        
        $student->birthday = Carbon::parse($student->birthday);
        $student->enrolled = is_null($student->enrolled) ? Carbon::parse($data->group->open) : Carbon::parse($student->enrolled);
        $student->expelled = is_null($student->expelled) ? Carbon::parse($data->group->close) : Carbon::parse($student->expelled);

        if($student->birthday->isBirthday()){
            $student->labels->push("fa fa-gift text-primary");
        }
        
        if(!is_null($student->notes)){
            $student->notes = json_decode($student->notes, true);
            $student->labels->push("fas fa-user-edit text-warning");
            if(isset($student->notes['names'])){
                
                foreach($student->notes['names'] as $changes){
                    
                    if($data->begin->lessThan($changes[1])){
                        $student->last_name = $changes[2];
                    }

                }

            }

        }
        
        return $student;
    }

    public function gradeSaveUpdateAjax(Request $request, GradebookGrades $gradebookGrades){

        $data = $request->validate([
            "gradebook_id" => ["required","integer"],
            "lesson_id" => ["required","integer"],
            "student_id" => ["required","integer"],
            "subject_id" => ["required","integer"],
            "grade_type" => ["required","integer"],
            "grade" => ["required","integer","max:12"],
            ]
        );

        $data['user_id'] = Auth::id();

        return response()->json($gradebookGrades->gradeSaveUpdate($data));

    }
    public function gradeSaveUpdateTypeAjax(Request $request){

        if(isset($request->lessonId)){
            if(isset($request->grades) && count($request->grades) > 0){
                if(DB::table("grade_setts")->where("lesson_id","=",$request->lessonId)->exists()){
                    DB::table("grade_setts")->where("lesson_id","=",$request->lessonId)->update([
                        "setts" => implode(",",$request->grades)
                    ]);
                }else{
                    DB::table("grade_setts")->insert([
                        "lesson_id" => $request->lessonId,
                        "setts" => implode(",",$request->grades)
                    ]);}
            }else{
                DB::table("grade_setts")->where("lesson_id","=",$request->lessonId)->delete();
            }
        }
        return response()->json($request, 200);

    }

}
