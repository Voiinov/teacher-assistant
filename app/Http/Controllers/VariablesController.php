<?php

namespace App\Http\Controllers;

use App\Models\Variables;
use Illuminate\Http\Request;

class VariablesController extends Controller
{

    /**
     * 
     * 
     */

     public function getVariablesByGroup(string $group){
        return Variables::where("id", $group)->get();
     }

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
    public function show(Variables $variables)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Variables $variables)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Variables $variables)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Variables $variables)
    {
        //
    }
}
