<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CurriculumMapping extends Model
{
    
    public function getMapping(int $mappingID){

        // return $this->where('id','=',$mappingID)->first();
    }

    public function getMappingThemes($mappingID){
        return $this->where('mapping_id','=',$mappingID)->get();
    }

    public function themes()
    {
        return $this->belongsToMany(
            CurriculumTheme::class, // Модель для `curriculum_themes`
            'curriculum_theme', // Назва зв’язуючої таблиці
            'mapping_id', // Ім’я зовнішнього ключа до `curriculum_mappings`
            'theme_id' // Ім’я зовнішнього ключа до `curriculum_themes`
        )
        ->orderBy("lesson","ASC")
        ->orderBy("module","DESC")
        ->orderBy("level","DESC")
        ->orderBy("title","ASC")
        ->withPivot('lesson');
    }

    public function subject(){
        return $this->belongsTo(Subject::class,"subject_id");
    }

}
