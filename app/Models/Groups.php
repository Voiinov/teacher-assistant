<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Options;
use Carbon\Carbon;

class Groups extends Model
{
    
    public $options;

    public function setYear(int $year = null)
    {
        $this->options = (new Options)->getDefaults($year);
    }

    private function groupSelector()
    {
        return $this->select(
            "groups.id",
            "groups.mask",
            "groups.open",
            "groups.close",
            "groups_setts.name AS post",
            "groups_setts.value",
            "users.id AS userID",
            "users.name",
        )
        ->leftJoin("groups_setts", "groups_setts.group_id", "=", "groups.id")
        ->leftJoin("users", function ($join) {
            $join->on("users.id", "=", "groups_setts.value")
                ->where("groups_setts.type", 1);
        });
    }

    public function getGroupData(int $groupID, int|null $onDate = null)
    {
        // $groupData = [];
        // $result = [];
        
        $groupData = $this
        ->with(["groupSettsProfessions","groupSettsUsers"])
        ->where('id','=',$groupID);

        if($groupData->exists()){
            $data = $groupData->get()->first();

            $studyYear = $this->getStudyYear($data->open, $onDate);
            $data->open=Carbon::parse($data->open);
            $data->close= Carbon::parse($data->close);

            $data->studyYear = $studyYear;
            $data->groupIndex = $this->groupIndex($data->mask, $studyYear);

        }

        $data->groupSettsProfessions->each(function($setts){
            if(isset($setts->design)){
                $setts->design = json_decode($setts->design);
            }
            return $setts;
        });

        return $data;

    }

    public function getSimpleActualGroupsList(bool $reverse = false, int $year = null)
    {
        $this->setYear($year);

        $list = [];

        $start = $this->options['app_academic_year']['start']['timstamp'];
        $end = $this->options['app_academic_year']['end']['timstamp'];


        $data = $this->select("id","mask","open","close")
            ->where('open',"<",$end)
            ->where('close',">",$start);
       
        if($reverse){
            foreach($data->get() as $item){
                    $index = str_replace(
                        "/",
                        "",
                        $this->getGroupIndex(
                            $item->mask, 
                            $item->open,
                            $end
                        )
                    );
                    $list[$index] = $item->id;
                }
        }else{
            foreach($data->get() as $item){
                    $list[$item->id] = $this->getGroupIndex(
                        $item->mask,
                        $item->open,
                        $end
                    );
                }
        }
        
        return $list;

    }

    /**
     * Отримує активні групи на основі дати закриття.
     *
     * Цей метод вибирає групи з бази даних, які активні на зазначену дату або на сьогоднішній день, якщо дата не вказана.
     * Групи організовуються за навчальними роками, і для кожної групи зберігається додаткова інформація, 
     * така як індекс групи та дані користувачів.
     *
     * @param string|null $onDate Дата, на яку потрібно отримати активні групи. Якщо не вказано, використовується сьогоднішня дата.
     * 
     * @return array Повертає асоціативний масив, де ключі — це навчальні роки, 
     *              а значення — масиви груп з додатковою інформацією та користувачами.
     */
    public function getActiveGroups(string $onDate = null)
    {
        $list = [];
        $closeDate = $onDate ? date("Y-m-d", strtotime($onDate)) : date("Y-m-d");

        $profession = new Profession();

        $this->setYear();

        $results = $this->groupSelector()->with(["groupSettsProfessions","groupSettsUsers"])->where("groups.close", ">=", $closeDate)
            ->orderByDesc("groups.open")
            ->get();
            // ->toArray();

        foreach ($results as $group) {

            $studyYear = $this->getStudyYear($group['open'], $closeDate);
            
            $groupIndex = sprintf($group['mask'], $studyYear);

            $group->groupIndex = $groupIndex;
            $group->open = Carbon::parse($group->open);
            $group->close = Carbon::parse($group->close);

            $group->groupSettsProfessions->map(function($prof) use($profession){
                $prof->design = $profession->getProfessionDesign($prof->design);
                return $prof;
            });
            
            $list[$studyYear][$group['id']] = $group;

        }

        return $list;
    }
   
    public function groupIndex(string $groupMask, int $course){
        return sprintf($groupMask, $course);
    }

    public function getGroupIndex(string $groupMask, string|null $openDate, string|null $onDate = null)
    {
        $course = $this->getStudyYear($openDate, $onDate) ;
        return $this->groupIndex($groupMask, $course);

    }

    /**
     * Summary of getStudyYear
     * @param string $openDate group open date
     * @param string $onDate
     * @return int
     */
    public function getStudyYear(string $openDate=null, string $onDate=null)
    {
        
        $begin = Carbon::parse($openDate);
        $end = Carbon::parse($onDate);

        $studyYear = $begin->diff($end)->y + 1;
        
        // return date_format($end, "n") < $this->options['app_academic_year']['end']['m'] ? ++$studyYear : $studyYear ;
        return $end->month >= 8 ? ++$studyYear : $studyYear ;

    }

    public function arrayByYearSubjectUsersForGroup($data){
        return $data->map(function($items) {
            $arr = [];
            foreach ($items as $item) {
                // Перетворюємо модель у масив перед модифікацією
                $subjectArray = $item->subject->toArray();
                
                if (!isset($arr[$item->subject_id])) {
                    $arr[$item->subject_id] = $subjectArray;
                    $arr[$item->subject_id]["users"] = []; // Додаємо пустий масив для користувачів
                }
        
                $arr[$item->subject_id]["users"][$item->user_id] = $item->user;
            }
        
            return $arr;
        });
    }

    public function getGroupYearSubjectUsers(int $groupId){
        return $this ->selectRaw("subject_id, user_id, YEAR(begin) as year")
        ->with("subject","user")
        ->where("group_id","=", $groupId)
        ->orderBy("year","desc")
        ->distinct("subject_id");
    }

    public function getGroupSetts($groupId){
        $data = $this->with(["students","groupSettsUsers","groupSettsProfessions"])
        ->where("id","=",$groupId);
        return $data->exists() ? $data->first() : false; 
    }

    public function students()
    {
        return $this->hasMany(Student::class,"group_id","id");
    }

    public function groupSettsUsers(){
        return $this->belongsToMany(
            User::class,
            "groups_setts",
            "group_id",
            "value"
            )
        ->withPivot("name")
        ->where("type","=",1);
    }
    
    public function groupSettsProfessions(){
        return $this->belongsToMany(
            Profession::class,
            "groups_setts",
            "group_id",
            "value"
        )
        ->where("type","=",2)
        ->withPivot("name")
        ->orderBy("groups_setts.name","asc");
    }

    public function timetable()
    {
        return $this->belongsToMany(Timetable::class,"group_id","id");
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class, "curriculum_id", 'id');
    }
    
}
