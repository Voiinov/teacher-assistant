<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Timetable;
use App\Models\User;
use Illuminate\Http\Request;
use App\Parsers\timeTableGDoc;

use function PHPUnit\Framework\returnSelf;

class TimetableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        
        return view("education.timetable.index",[
            "pageTitle"=>"Timetable",
            "breadcrumb"=>[
                    ['name'=>'Timetable','current'=>true],
                ],
        ]);
    }

    /**
     * Show the form for creating a new resource
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
    public function show(Timetable $timetable)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Timetable $timetable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Timetable $timetable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Timetable $timetable)
    {
        //
    }

    
    //***********IMPORT *************/
    /**
     * Show the import timetable view.
     *
     * @param \App\Models\User $user The authenticated user.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View The view for importing timetable data.
     */
    public function indexImport(Request $request)
    {   

        // Determine if the user has the necessary permission
        return view("education.timetable.create.import", [
            "pageTitle" => "Import",
            "breadcrumb" => [
                ['name' => 'Timetable', 'url' => route("timetable.index")],
                ['name' => 'Import', 'current' => true],
            ],
        ]);
    }

    /**
     * Handle AJAX request to import timetable data from Google Docs CSV.
     *
     * @param timeTableGDoc $timeTableGDoc The service to fetch and parse the timetable CSV data.
     * @return \Illuminate\Http\JsonResponse JSON response with the parsed timetable data.
     */
    public function importDataAjax(timeTableGDoc $timeTableGDoc)
    {
        return $timeTableGDoc->getData();
    }


    public function storeImportDataAjax(Timetable $timetable) 
    {
        return  $timetable->storeImportData();
    }

    public function indexAjax(Request $request, Timetable $timetable)
    {
        $between = [
            $request->get('start'),
            $request->get('end')
        ];

        $lessonsList = $timetable->getTimetable($between, $request->get('user'));

        return response()->json(
            $timetable->fullcalendarArray($lessonsList)
        );
    }

}
