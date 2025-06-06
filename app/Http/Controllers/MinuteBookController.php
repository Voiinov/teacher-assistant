<?php

namespace App\Http\Controllers;

use App\Models\MinuteBook;
use Illuminate\Http\Request;
use App\Models\Variables;
use Carbon\Carbon;

class MinuteBookController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(MinuteBook $minutebook)
    {
        
        return view('minutebook.index', [
            "minuteBook"=>$minutebook->getDocList(),
            "permission"=>true,
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("minutebook.create",[
            "doc_types"=>Variables::where("type", 5)->get(),
            "permission"=>true,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Carbon $carbon)
    {
        $data = $request->validate([
            "doc_date"=>["required", "date"],
            "doc_type"=>["required", "integer"],
            "number"=>"required|unique:minute_books|string",
            "title"=>["required", "string"],
            "description"=>'nullable|string',
        ]);

        $data["doc_date"] = $carbon::parse($data['doc_date'])->format('Y-m-d');
        
        try{
            MinuteBook::create($data);
        }catch(\Illuminate\Database\QueryException $exception){
            return to_route('minutebook.index')->with('error', __($exception));
        }

        return to_route('minutebook.index')->with('massage', __('Data was updatet'));

    }

    /**
     * Display the specified resource.
     */
    public function show(MinuteBook $minuteBook)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MinuteBook $minuteBook)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MinuteBook $minuteBook)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MinuteBook $minuteBook)
    {
        //
    }
}
