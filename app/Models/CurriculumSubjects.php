<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CurriculumSubjects extends Model
{
    
    public function subject()
    {
        // return $this->belongsTo(Subject::class,"subject_id","id");
        return $this->belongsToMany(Subject::class, 'subjects', 'subject_id', 'id');
    }

}
