<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\Variables;
use Illuminate\Database\Eloquent\Collection;

class Handler {

    public function studentGradesList($data)
    {     

        $gradeType = $this->getVariablesByType([10,11]);
        
        $newData = $data->groupBy('subject_id')->map(function ($item) use($gradeType){
            
            $result = collect([
                "title"=>$item[0]->lessonSubject->title,
                "short_title"=>$item[0]->lessonSubject->short_title,
                "grades"=>[],
            ]);

            $result['grades'] = $item->map(function($grade) use($gradeType){
                $grade->grade_type = $gradeType[$grade->grade_type];

                $grade->begin = Carbon::parse( $grade->lessonSubject->begin);
                $grade->end = Carbon::parse($grade->lessonSubject->end);
                unset($grade->lessonSubject);
                return $grade;
            });

            return $result;

        });

        return $newData;
    }

    public function getVariablesByType($types){
        $data =  is_array($types) ? Variables::whereIn("type",$types) : Variables::where("type","=",$types);
        return $data->get()->keyBy("id");
    }

    public function studentSubejctHoursGradesTotalArr($data)
    {
        $arr = collect();
        $tmpItem = collect();
        // $data->each
        // $data->each(function($item) use ($arr){
        //     if(!isset($arr[$item->subjectShort->id])){
        //         $arr[$item->subjectShort->id] = collect([
        //             "total"=>0,
        //             "grades"=>[],
        //             "title"=>$item->subjectShort->title,
        //         ]);
        //     }
        //     $arr[$item->subjectShort->id]->grades = $item->grade;
        // });
        $arr = $data->groupBy('subject_id')->map(function ($item) {
            $result = (object)[
                "title"=>$item[0]->subjectShort->title,
                "grades"=>(object)[],
                // "total"=>[],
            ];
            $result->grades = $item->groupBy('grade_type')->map(function($grades){
                $newArr = $grades->map(function($grade){
                    if($grade->grade>0)
                        return $grade;
                });
                if(isset($newArr) && $newArr->count()>0){
                    return $newArr;
                }
            });
            return $result;
        });
        return $arr;
    }

}