<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

class Actions extends Model
{

    public function getSubjectActions( int $itemID, int $items=5, string $direction = "desc"){
        $data = $this->select(
            "actions.*",
            "users.first_name",
            "users.last_name",
            "users.name"
        )
        ->leftJoin("users","users.id","=","actions.user_id")
        ->where("actions.item_id",$itemID)
        ->whereLike("actions.description","curricula%")
        ->limit($items)->orderBy("actions.created_at",$direction)->get();
        
        $result = ["data"=>[]];

        if($data->count() > 0){
            try{
                foreach($data as $key => $item){
                    if(!is_null($item->route) || $item->route != ""){
                        $route = explode(",",$item->route);

                        if(Route::has($route[0])){
                            if(isset($route[1])){
                                $ids[]=$route[1];
                                $data[$key]->link = route($route[0],["themeId"=>$route[1]]);
                                $data[$key]->themeId = $route[1];
                            }else{
                                $data[$key]->link = route($route[0]);
                                $data[$key]->themeId = null;
                            }
                        }else{
                            if(isset($route[1])){
                                $ids[]=$route[1];
                            }
                        }            
    
                    }
                }
            }catch(\Exception $e){
                return ["data"=>[],"errors"=> $e->getMessage()];
            }

        }

        if(isset($ids)){
            return ["data"=>$data,"themes"=>$this->getThemesList($ids)];
        }

        return ["data"=>$data];

    }

    public function getThemesList($idList){
        $data = DB::table('curriculum_themes')
        ->select("id","title")
        ->whereIn("id",$idList)
        ->get();

        if($data->count() > 0){
            foreach($data as $item){
                $result[$item->id] = $item->title;
            }
            return $result;
        }
        return [];
    }

}
