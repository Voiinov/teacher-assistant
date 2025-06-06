<?php

namespace App\Http\Controllers;

use App\Helpers\AppOptions;
use App\Models\Gradebook;
use App\Models\Subject;
use Illuminate\Http\Request;
use App\Models\GradebookGrades;
use Illuminate\Support\Carbon;


class GradebookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view("education.gradebook.index",[
            "pageTitle"=>__("Grade book"),
            "breadcrumbs" => [
                ['name' => __('Journals'), 'current' => true],
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Gradebook $gradebook)
    {
        $groupID = $request->get("group");

        return view("education.gradebook.create", [
            "pageTitle" => __("Create grade book"),
            // "cardTitle" => __("Create grade book"),
            "breadcrumbs" => [
                ['name' => 'Groups', 'url' => route("groups.index")],
                ['name' => 'Groups', 'current' => true],
            ],
            "data" => $gradebook->getDataForGradeBookCreate($groupID),
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
    public function show(Request $request, Gradebook $gradebook)
    {

        $data = $gradebook->getGradeBookSubjectsList($request->gradebook->id);

        $request->merge([
            "close"=>is_null($request['close_year']) ? date("Y-m-d 23:59:59") : $data['close_year'],
            "gradebookID"=>$request->gradebook->id,
            "open"=>$data->open,
        ]);

        $viewData = [
            "pageTitle"=> isset($data->group_index) ? __("Grade book of group :group",["group"=>$data->group_index]) : __("Grade book"),
            "breadcrumbs"=>[
                ["name"=> "Groups","url"=>route("groups.index")],
                ["name"=> "Journals","url"=>route("gradebooks.group",$request->gradebook->group_id)],
                ["name"=> "Journal", "current"=>true],
            ],
            "subjectID"=> false,
            "grades"=> null,
            "data"=> $data->toArray(),
        ];

        if($request->subjectID){
            $viewData["subject"] = Subject::where("id","=", $request->subjectID)->first();
            $viewData["grades"] = $gradebook->subjectGradesByStudentArr($request)->toArray();
        }

        return view("education.gradebook.show",$viewData);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gradebook $gradebook)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gradebook $gradebook)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gradebook $gradebook)
    {
        //
    }
    public function groupGradeBooks(Request $request)
    {
        return view("education.gradebook.group",[
            "pageTitle"=>"Group grade books",
            "cardTitle" => "Group grade books",
            "breadcrumbs" => [
                ['name' => 'Groups', 'url' => route("groups.index")],
                ['name' => 'Journals', 'current' => true],
            ],
            "groupID" => $request->groupID,
            "list"=> Gradebook::where("group_id","=",$request->groupID)->get()->toArray(),
        ]);
    }

}
