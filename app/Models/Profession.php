<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
    
    public function getProfessionDesign($data)
    {
        $defaultDesign = ["i"=>"fas fa-user-graduate"];
        
        if(is_null($data))
            return $defaultDesign;

        $design = json_decode($data, true);
        return collect(array_merge($defaultDesign,$design));
        
    }

}
