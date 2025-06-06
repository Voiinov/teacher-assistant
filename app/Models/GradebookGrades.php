<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GradebookGrades extends Model
{
    public $incrementing = false;
    // protected $primaryKey = null;
    protected $fillable = [
        "gradebook_id",
        "lesson_id",
        "student_id",
        "subject_id",
        "user_id",
        "grade_type",
        "grade",
        "created_at",
        "updated_at",
    ];


    public function gradeSaveUpdate($data){

        $result = [];

        unset($data['_token']);

        $check = $this->where('gradebook_id', "=", $data['gradebook_id'])
            ->where('lesson_id', "=", $data['lesson_id'])
            ->where('student_id', "=", $data['student_id'])
            ->where('subject_id', "=", $data['subject_id'])
            ->where('grade_type', "=", $data['grade_type'])
            ->exists();

        if($check){

            $data['grade'] = intVal($data['grade']);
        
            // $data["grade_type"] = str_replace("_","",$data["grade_type"]);
            
            if( $data['grade'] < 0 || is_null($data['grade'])){
                
                unset($data['grade']);

                $result = $this->where($data)->delete();

            }else{
                $grade = $data['grade'];
                unset($data['grade']);
                try{
                    $this->where("lesson_id",$data['lesson_id'])
                        ->where("student_id",$data['student_id'])
                        ->where("grade_type",$data['grade_type'])
                        ->where("gradebook_id",$data['gradebook_id'])
                        ->where("subject_id",$data['subject_id'])
                        ->where("user_id",$data['user_id'])
                        ->update(['grade'=>$grade]);
                }catch(\Exception $e){
                    return response()->json(['error' => $e->getMessage()], 500);
                }
            }
        }else{
            try{
                $result = $this->create($data);
            }catch(\Exception $e){
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        return $result;
    }
    
    public function gradebook()
    {
        return $this->belongsTo(Gradebook::class, 'gradebook_id');
    }

    public function group()
    {
        return $this->belongsTo(Groups::class,"group_id");
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function subjectShort()
    {
        return $this->belongsTo(Subject::class,"subject_id")->select("id","title","subgroup");
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class,"subject_id");
    }


}
