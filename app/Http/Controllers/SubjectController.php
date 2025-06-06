<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use App\Models\User;
use App\Models\Actions;
// use App\Http\Controllers\EventsController as Events;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Subject $subject)
    {
        return view("education.subjects.index",[
            "subjects"=>$subject->getSubjectsList(),
            "pageTitle"=>"Subjects list",
            "permission"=>true,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(Request $request, Subject $subjects, Route $route, User $user, Actions $actions)
    {

        $subjectData = $subjects->getSubjectData($route->subject);
        
        return view("education.subjects.show",[
            "pageTitle"=>$subjectData->first()->title,
            "subjectId"=>$route->subject,
            "user"=>$user,
            "data"=>$subjectData,
            "counter"=>$subjects->getSubjectDataCounter($route->subject),
            // "events"=> $events->getList("subject",$request->subject),
            "events"=>$actions->getSubjectActions($request->subject),
            "options"=> $subjects->getDefaultSubjectOptions(json_decode($subjectData->first()->options,true)),
            "permission"=>true,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subject $subject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subject $subject)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subject $subject)
    {
        //
    }
}
