<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Groups;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    public function getStudentsDataTable($request){

        $records = $this->count();
        $columns = [
            'id',
            'last_name',
            'first_name',
            'middle_name',
        ];

        $list = $this
            ->select($columns)
            ->orderBy($columns[$request->post('order')[0]['column']], $request->post('order')[0]['dir'])
            ->paginate($request->post('length'),['*'],"page",$request->post('page'))
            ->toArray();

        $data = [
            'draw' => $request->post("draw")+1,
            'recordsTotal' => $records,
            'recordsFiltered' => $records,
            'data'=>$list['data'],
            'rqsr'=>$request->post(),
        ];

        return $data;

    }

    public function gradebook()
    {
        return $this->belongsTo(Gradebook::class, 'group_id', 'group_id');
    }

    public function studentsList()
    {
        return $this->hasMany(Gradebook::class);
    }
    public function grades()
    {
        return $this->hasMany(GradebookGrades::class, 'student_id');
    }

    public function group()
    {
        return $this->belongsTo(Groups::class,"group_id");
    }
    
}
