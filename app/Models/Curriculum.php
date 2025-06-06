<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Curriculum extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'theme',
        'title',
        'grouped',
        'module',
        'description',
        'user_id',
        'subject_id',
        'subject',
        'hours',
        'options',
        'active',
    ];


    public function subjectHours()
    {
        return $this->hasMany(CurriculumSubjects::class,"curriculum_id","id");
    }
    
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'curriculum_subjects', 'curriculum_id', 'subject_id')
            ->withPivot('hours')
            ->orderBy("title");
    }

    /**
     * 
     */
    public function storeTheme(array $data){
        
        return DB::table("curriculum_themes")->insertGetId($data);
        
    }

    /**
     * Returns themes list.
     * @param int $subjectID 
     * 
     */

     public function getThemesList(int $subjectID, int $module = null, int $active = 1)
     {
        $data = DB::table("curriculum_themes")
        ->where('subject','=',$subjectID)
        ->where('active','=',$active);
        
        if(!is_null($module)){
           return $data->where('module','=',$module)->get()->keyBy("id");
        }

        $result = $data->get();

        // if($result->isEmpty()){
        //     return [0=>[]];
        // }
        return $result;

    }


    /**
     * Summary of constructThemesList
     * @param object|array $data
     * @return array
     */
    private function constructThemesList(object|array $data = []){
        
        $result = [];

        foreach($data as $item){
            $result[$item->id] = $item;
        }

        return $result;

    }
    
    public function getModulesList(int $subjectID){
        return DB::table("curriculum_themes")
        ->select("id","title")
        ->where('subject','=',$subjectID)
        ->where('active','=',1)
        ->where('module','=',1)
        ->get()
        ->toArray();
    }

    /**********************************
    *---------- Mapping -------------- *
    ***********************************/
    public function storeMapping(array $data){

        return DB::table("curriculum_mappings")->insertGetId($data);

    }

    public function updateMappingAjax(array $data = [], array $options = []){

        return DB::table("curriculum_mappings")
        ->where("id","=",$options['mappingId'])
        ->where("user_id","=",Auth::id())
        ->update(["options"=>json_encode($data),"updated_at"=>now()]);
        
    }

    public function getMappingList(int $subjectID, int $userID = null){
        
        return DB::table("curriculum_mappings AS mapping")
        ->select(
            "mapping.id",
            "mapping.status",
            "mapping.title",
            "mapping.hours",
            "mapping.user_id",
            "users.last_name",
            "users.first_name",
            "variables.value",
            "variables.name",
            "mapping.created_at",
            "mapping.updated_at"
            )
        ->leftJoin("users","mapping.user_id","=","users.id")
        ->leftJoin("variables","mapping.status","=","variables.id")
        ->where("mapping.subject_id","=",$subjectID)
        ->get();
        
    }

    public function getMappingData(int $mappingID){

        $data = DB::table("curriculum_mappings AS mapping")
        ->where("id","=",$mappingID)
        ->get()
        ->first();
        
        $data->options = json_decode($data->options);
        
        return $data;

    }

    public function themes(){
        return $this->hasMany(CurriculumTheme::class,"mapping_id","id");
    }

}
