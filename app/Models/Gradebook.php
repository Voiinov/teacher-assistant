<?php

namespace App\Models;

use App\Helpers\AppOptions;
use DragonCode\Support\Exceptions\ForbiddenVariableTypeException;
use Illuminate\Database\Eloquent\Model;
use App\Models\Groups;
use App\Models\Student;
use Illuminate\Support\Carbon;

class Gradebook extends Model
{

    public function getGradeBooksForGroup(int $groupID)
    {
        return $this->where('group_id', "=", $groupID)->get();
    }

    public function getGroupGradeBook(int $groupID, string $date = null)
    {
        
        if(is_null($date))
            $date = Carbon::now();
        
        return $this->with("grades")
        ->where("group_id","=",$groupID)
        ->where("open","<=", $date)
        ->where(function($query) use ($date) {
            $query->where("close_year", ">", $date)
                    ->orWhereNull("close_year");
        })
        ->get();
    
    }

    public function getDataForGradeBookCreate(int $groupID)
    {
        return (new Groups())->getGroupData($groupID);
    }

    public function getGradeBookSubjectsList(int $ID)
    {
        $result = [];

        $result = Gradebook::where("id","=",$ID)
        ->with(['group.curriculum.subjects','students'])
        ->first();
        return $result;

    }

    public function subjectGradesByStudentArr($request)
    {

        $gradeTypes = AppOptions::getGradeTypes();
        // Отримуємо перший запис з необхідними студентами та розкладом
        $data = $this->getSubjectGradesByStudent($request)->first();
        // Створюємо масив розкладу
        $timetable = [];
        foreach ($data->timetable as $lesson) {
            $timetable[$lesson->id]['header']["_10"] = Carbon::parse($lesson->begin);
            if(!is_null($lesson->setts)){
                $setts = explode(",",$lesson->setts);

                $timetable[$lesson->lesson_id]['footer'][] = $gradeTypes[12][$setts[0]] ?? null;

                if(count($setts)>1){
                    foreach (array_slice($setts, 1) as $sett) {
                        $timetable[$lesson->lesson_id]['header']["_" . $sett] = $gradeTypes[11][$sett];
                    }

                }
            }
        }
        $data->open = Carbon::parse($data->open);
        // dd($data->toArray());
        // Створюємо масив студентів з оцінками
        $students = [];
        foreach ($data->students as $student) {

            $student->expelled = Carbon::parse($student->expelled);

            // if(is_null($student->envolled)){
            //     $student->envolled = $data->open;
            // }

            if($data->open->greaterThanOrEqualTo($student->expelled)){
                $students[$student->id] = $student;
            }

            // Створюємо масив оцінок
            $grades = [];
            $avg = [];
            foreach ($student->grades as $grade) {

                $grades[$grade->lesson_id]["_" . $grade->grade_type] = [
                    "grade" =>$grade->grade,
                    "user_id" =>$grade->user_id,
                    "grade_type_name" =>$grade->grade_type_name,
                ];

                // Додаємо тип оцінки у розклад, якщо це не оцінка типу 10
                if ($grade->grade_type == 10) {
                    if($grade->grade>0){
                        $avg[] = $grade->grade;
                    }else{
                        $grades[$grade->lesson_id]["_" . $grade->grade_type]['grade'] = "Н";
                    }
                }else{
                    $timetable[$grade->lesson_id]['header']["_" . $grade->grade_type] = $grade->grade_type_name;
                }

            }

            $gradesCount = count( $avg);
            // Додаємо оцінки студента в основний масив
            $students[$student->id]['grades'] = $grades;
            // Додаємо середній бал
            $students[$student->id]['avg'] = $gradesCount>0 ? round(array_sum( $avg)/count( $avg),1) : "";
        }

        // Повертаємо JSON-відповідь
        return collect(['colls' => $timetable, 'data' => $students, 'gradeTypes' => $gradeTypes]);
        // dd(collect(['colls' => $timetable, 'data' => $students, 'gradeTypes' => $gradeTypes]));
    }

    public function subjectGradesByStudent($request)
    {       
        
        $data = $this->getSubjectGradesByStudent($request)->map(function ($item) {
        
            $timetable = $item->timetable->keyBy('id');

            $students = $item->students->keyBy('id')->map(function ($student){
                
                $data = $student->toArray();
                $data['grades'] = $student->grades->groupBy('lesson_id')->map(function ($grades) {
                    return $grades->keyBy('grade_type');
                });
                return $data;

            });
            
            return ['students'=>$students, 'timetable'=>$timetable];
        })->first();
        return $data;
    }
    
    public function getSubjectGradesByStudent($request)
    { 

        if ($request->has('sub') && !empty($request->query('sub'))) {
            $sub = explode(",", $request->query('sub'));
            $sub[] = $request->subjectID;
            $subject = collect($sub);
        } else {
            $subject = collect([$request->subjectID]);
        }

        $data = self::with(["students.grades"=>function($query) use ($subject){
            $query->leftJoin("variables","gradebook_grades.grade_type","=","variables.id")
                ->whereIn('subject_id',$subject)
                ->select(
                    'gradebook_grades.*',
                    'variables.name as grade_type_name'
                );
                // )->orderBy("gradebook_grades.created_at");
            },
            "timetable"=>function($query) use ($request,$subject){
                $query->where("substitute",'<',2)
                ->whereIn('subject_id',$subject)
                ->leftJoin("grade_setts","timetables.id","=","grade_setts.lesson_id");
                
                $query
                    ->whereBetween('begin',[$request->open,$request->close])
                    ->orderBy('id');
            },
        ]);
        
        if(isset($request->gradebookID))
            $data->where("id", $request->gradebookID);
        elseif(isset($request->gradebook->id)){
            $data->where("id", $request->gradebook->id);
        }elseif(isset($request->groupID)){
            $data->where("group_id", $request->groupID)
            ->whereNull("close_year");
        }else{
            $data->whereNull("close_year");
        }

        return $data->get();

    }

    public function group_gradebook()
    {
        return $this->hasMany(GradebookGrades::class,"gradebook_id");
    }

    public function group()
    {
        return $this->belongsTo(Groups::class,"group_id");
    }

    public function timetable()
    {
        return $this->hasMany(Timetable::class, 'group_id', 'group_id');
    }

    public function grades()
    {
        return $this->hasMany(GradebookGrades::class, 'gradebook_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'group_id', 'group_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class,"subject_id");
    }
    
}

