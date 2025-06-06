<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Variables extends Model
{
    public function getGradeTypes(){

        $grades = $this->where("type","=",11)->get();

        return $grades->pluck("variable_value","id");
        
    }
}
