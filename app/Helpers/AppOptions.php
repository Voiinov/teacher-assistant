<?php
namespace App\Helpers;

use Illuminate\Support\Carbon;
use App\Models\Options;
use App\Models\Variables;

class AppOptions
{
    protected $options;

    public function __construct()
    {
        $this->getAppOptions();
    }

    public function getOptionValue($name){
        return $this->options[$name] ?? null;
    }

    public function studyBegin(){
        return Carbon::parse($this->options['app_study_begin']);
    }
    
    public function studyEnd(){
        return Carbon::parse($this->options['app_study_end']);
    }

    static function getGradeTypes(){
        return Variables::whereIn("type", [11, 12])->get()->groupBy("type")->map->pluck('name',"id")->toArray();
    }

    private function getAppOptions()
    {
        $this->options = Options::where("type", "=",0)->pluck("value","name");

    }

}
