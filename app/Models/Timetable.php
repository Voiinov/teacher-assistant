<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Timetable extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'group_id',
        'subject_id',
        'user_id',
        'begin',
        'end',
        'subtitute',
    ];

    public function storeImportData(){
        $tempData = Storage::disk('local')->get("/temp/timetable.json");
        $data = json_decode($tempData, true);
        
        if($data['summary']['items']!=$data['summary']['id']){

            return response()->json( 
                [__("Duplicate found!"),
                $this->findDuplication($data['id'])],
                500,
                [],
                JSON_UNESCAPED_UNICODE);
        }else{
            return $this->storeAjax($data);
        }
    }

    public function storeAjax($data)
    {
        if(count($data['data'])>1000){
            set_time_limit(180);
            foreach(array_chunk($data['data'],1000) as $chunk){
                try{
                    $this->insert($chunk);
                    $result = response()->json('Data imported', 200,[],JSON_UNESCAPED_UNICODE);
                }catch(\Illuminate\Database\QueryException $exception){
                    return response()->json($exception, 500,[],JSON_UNESCAPED_UNICODE);
                }
            }
        }else{
            try{
                $this->insert($data['data']);
                $result = response()->json('Data imported', 200,[],JSON_UNESCAPED_UNICODE);
            }catch(\Illuminate\Database\QueryException $exception){
                $result = response()->json($exception, 500,[],JSON_UNESCAPED_UNICODE);
            }
            
        }
        
        return $result;
    }

    public function findDuplication(array $data){
        $duplication=[];
        foreach($data as $id=>$rows){
            if(count($rows)>1)
                $duplication[$id] = $rows;
        }
        return $duplication;
    }

    public function getTimetable(array $between, int|bool $user = false, int|bool $subject = false)
    {
        $timetable = $this->with("subject.group");

        if($user) 
            $timetable->where("user_id","=",$user);

        if($subject) 
            $timetable->where("subject_id","=",$subject);

        return $timetable->whereBetween("begin", $between)->orderBy("begin")->get();

    }

    public function getGroupCalendar($start, $end, $groupId)
    {
        $calendar = $this->with("subject.user")
            ->where("group_id","=",$groupId)
            ->where("end", ">=",$start)
            ->where("begin","<=", $end);

        return $calendar->orderBy("begin")->get() ?? null ;
    }


    public function fullcalendarArray($data, $url = true)
    {

        $arr = [];

        foreach($data as $key => $lesson){
            $year = Carbon::now()->diff($lesson->group->open);
            $arr[$key] = [
                "id"=>$lesson->id,
                "title" => sprintf($lesson->group->mask,$year->y+1) . ": " . $lesson->subject->title,
                "start" => $lesson->begin,
                "end" => $lesson->end,
                "url" => $url ? route('gradebooks.grade.create',[$lesson->id]) : "#",
            ];

            if(isset($lesson->user))
            {
                $arr[$key]['user'] = $lesson->user->only(["name","first_name","middle_name","last_name"]);
            }

            if($lesson->substitute>0)
            {
                $arr[$key]["className"] = "bg-warning";
            }
        }

        return $arr;

    }

    public function timetableGroupByTimeDate($data)
    {

        $newData = [];

        foreach($data as $lesson){
            $lesson->begin = Carbon::parse($lesson->begin);
            $lesson->end = Carbon::parse($lesson->end);
            
            $timeKey = $lesson->begin->format("H:i");
            $dateKey = $lesson->begin->format("j");

            $newData[$timeKey][$dateKey][$lesson->group_id] = $lesson;
        };

        ksort($newData);

        // dd($newData);

        return $newData;
    }

    public function getLessonData(int $lessonID)
    {
        return $this->with(['subject.isSubgroup', 'group.students'])
        ->where('timetables.id', '=', $lessonID)
        ->get();
    }

    
    public function group()
    {
        return $this->belongsTo(Groups::class,"group_id");
    }

    public function gradebooksByGroupId(){
        return $this->hasMany(Gradebook::class,"group_id","group_id");
    }

    public function gradeBookActualOnly(){
        return $this->hasMany(Gradebook::class,"group_id","group_id")->whereNull("close_year");
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class,"subject_id");
    }
    public function user()
    {
        return $this->belongsTo(User::class,"user_id");
    }

    public function grades(){
        return $this->hasMany(GradebookGrades::class,"lesson_id");
    }

    public function gradeBooks(){
        return $this->hasMany(Gradebook::class,"group_id","group_id");
    }
}
