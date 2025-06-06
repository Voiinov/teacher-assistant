<?php

namespace App\Http\Controllers;

use App\Models\Actions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Symfony\Contracts\Service\Attribute\Required;

class ActionsController extends Controller
{
   public $model;

   public function action($request, $routeName){
      
      $action = str_replace(".", "_", $routeName);

      if(method_exists($this, $action)){
         $this->$action($request);
      }

   }

   public function init($routeName){
      
      // $route = Route::currentRouteName();
      
      $action = str_replace(".", "_", $routeName);
      
      dd($action);

      if(method_exists($this, $action)){
         $this->$action();
      }
   }

   public static function saveAction($route)
   {
      //
   }

   public function getList(string $route, int $itemId, int $items=5, bool $full = false){
      // if($event == "subject")
      //    return $this->model->getSubjectEvents($items,$itemId);
   }

   public function curriculum_mapping_store(Request $request){
      $this->store(
         "New curriculum mapping",
         "curriculum.mapping.store",
         'curriculum.mapping.index',
         $request->subjectId
      );
   }

   public function curriculum_themes_store_with_id($id,$subjectId)
   {
      $this->store(
         "New theme",
         "curricula.theme.store",
         "curriculum.themes.show,{$id}",
         $subjectId
      );
   }

   public function timetable_import_store_ajax()
   {
      // dd(Route::currentRouteName());
      $this->store(
         "Timetable update",
         "message.timetable.update",
         'timetable.index');
         // Route::currentRouteName());
   }


   private function store(string $action, string|null $description, string $route, int $item_id = null){
      
      Actions::insert([
         "action"=>$action,
         "description"=>$description,
         "route"=>$route,
         "item_id"=>$item_id,
         "user_id"=>Auth::id(),
      ]);
   }

}
