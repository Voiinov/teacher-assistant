<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Subject extends Model
{
    use HasFactory;

    public function getDefaultSubjectOptions(array $options = null){
        $defaults = [
            "fa_icon" => "fa fa-laptop",
            "color_class" => "primary",
            "description" => NULL,
        ];
        return is_null($options) ? $defaults : array_merge($defaults, $options);
    }

    public function getSubjectsList(){
        return $this
        // ->leftJoin("variables as subject_status","subjects.status","=","subject_status.id")
        ->leftJoin("variables as subject_type","subjects.type","=","subject_type.id")
        ->select(
            "subjects.id",
            "subjects.active",
            "subjects.title",
            "subjects.short_title",
            "subject_type.name AS type",
            "subject_type.short_name AS type_short",
        )
        ->whereNull("subjects.subgroup")
        ->orderBy('subjects.title','asc')
        ->get();
    }

    public function getSubjectData(int $subjectID){
        return $this
        ->where('id', "=",$subjectID)
        ->orWhere("subgroup","=", $subjectID)
        ->get();
    }

    public function getSubjectDataCounter(int $subjectID){
        return  [
            "mappings" => DB::table("curriculum_mappings")
            ->where('subject_id',"=", $subjectID)
            ->count(),
            "themes" => DB::table("curriculum_themes")
            ->where('subject',"=", $subjectID)
            ->count(),
            "curriculums"=>0
        ];

    }

    public function getSubjectDataWithRelations(int $subjectId, array $relations = null){

        $relations = !is_null($relations) ? array_merge($relations,["themes","type"]) : ["themes","type"] ;

        return $this->with($relations)
        ->where("subjects.id","=",$subjectId)
        ->get();

    }

    public function grades()
    {
        return $this->belongsToMany(Student::class, 'gradebook_grades', 'subject_id', 'student_id')
        ->withPivot('grade')
        ->orderBy("last_name");
    }

    public function group(){
        return $this->belongsTo(Groups::class,"group_id");
    }

    public function user(){
        return $this->belongsTo(User::class,"user_id");
    }

    public function isSubgroup()
    {
        return $this->belongsTo(Subject::class,"subgroup");
    }

    public function themes()
    {
        return $this->hasMany(CurriculumTheme::class,"subject");
    }
    
    public function type()
    {
        return $this->hasMany(Variables::class,"id","type");
    }

}
