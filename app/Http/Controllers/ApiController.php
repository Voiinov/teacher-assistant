<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Timetable;

class ApiController extends Controller
{

    public function api(Request $request)
    {
        
        if($this->keyCheckFaild($request)){
            return $this->returnDataType(['error'=>"Key doesn't exists"]);
        }

        switch($request->get){
            case("calendar"):
                $data = $this->calendar($request);
            break;
            default:
        }

        return $this->returnDataType($data);
    }

    private function keyCheckFaild(Request $request)
    {
        if(!isset($request->key) || $request->key != md5("24041985"))
            { return true; }
        else
            { return false; }
    }

    public function calendar(Request $request){
        
        $timetable = new Timetable();

        $lessonsList = $timetable->getGroupCalendar($request->get('start'),$request->get('end'), $request->groupId);

        return $timetable->fullcalendarArray($lessonsList, false);

    }

    private function returnDataType($data, $dataType = "json")
    {
        return response()->json($data);
    }
    
}
