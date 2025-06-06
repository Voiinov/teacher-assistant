<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CurriculumTheme extends Model
{
    public function getTheme(int $themeID){
        return $this->where('id','=',$themeID)->first();
    }

    public function updateTheme($data, $id){
        return $this->where('id','=',$id)->update($data);
    }

    public function authorId(int $thmeID){
        return $this->select("user_id")->where('id','=',$thmeID)->first();
    }

    public function storeUpdateThemeConn($mappingId, $themeId, $lesson){
        $table = DB::table("curriculum_theme")
        ->where("mapping_id","=",$mappingId)
        ->where("lesson","=",$lesson);

        if($themeId==0 || $themeId==null){
            $table->delete();
        }else{

            $existingRecord = $table->first();

            if ($existingRecord) {
                return $table->update([
                        "mapping_id"=>$mappingId,
                        "theme_id"=>$themeId
                    ]);
            } else {
                return DB::table("curriculum_theme")
                ->insert([
                    "mapping_id"=>$mappingId,
                    "theme_id"=>$themeId,
                    "lesson"=>$lesson,
                ]);
            }
        }
    }

    public function mappings()
    {
        return $this->belongsToMany(
            CurriculumMapping::class, // Модель для `curriculum_mappings`
            'curriculum_theme', // Назва зв’язуючої таблиці
            'theme_id', // Ім’я зовнішнього ключа до `curriculum_themes`
            'mapping_id' // Ім’я зовнішнього ключа до `curriculum_mappings`
        );
    }
}
