<?php

namespace App\Http\Controllers;

use App\Models\Timetable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
class UsersController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        
        // $usersList = User::query()->orderBy('last_name','asc')->paginate(12);
        // $usersList = User::query()->where("status","=","100")->orderBy('last_name','asc')->paginate(12);

        // return view('users.index', ["usersList"=>$usersList]);
        return view('users.index', ["usersList"=>$user->getUsersList(100)]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create',[
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
    public function show(User $user)
    {
        return view('users.show', [
            'user' => $user,
            "permission"=>true,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', [
            'user'=> $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)   
    {
        $data = $request->validate([
            'first_name'=> ['required', 'string'],
            'middle_name'=> ['required', 'string'],
            'last_name'=> ['required', 'string'],
        ]);

        $user->update($data);

        return to_route('users.show', $user)->with('massage', __('Data was updatet'));
    }

    public function profile(Request $request, User $user, Timetable $timetable){

        $data = $user
            ->with("role")
            ->where("id","=",Auth::id())->get()->first();

        $timetableData = $timetable->timetableGroupByTimeDate($timetable->getTimetable([Carbon::now()->startOfMonth(),Carbon::now()->endOfDay()],Auth::id()));

        return view("users.profile",[
            "pageTitle" =>__("Profile"),
            "data"=>$data,
            "timetable"=>$timetableData,
        ]);
    }
}
