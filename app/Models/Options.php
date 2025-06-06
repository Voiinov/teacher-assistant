<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


class Options extends Model
{
    use HasFactory;

    private $app_defaults;

    public function getDefaults(int $year = null)
    {
     
        $this->applyDefaults();

        $this->setCurrentAcademicPeriod($year);
        $this->app_defaults['app_academic_year']['start']['timstamp'] = $this->academicPeriodStart();

        $this->app_defaults['app_academic_year']['end']['timstamp'] = $this->academicPeriodEnd();

        return $this->app_defaults;
        
    }
    
    private function applyDefaults(){
        // $this->select("name", "value")->get();
        $this->app_defaults = array_merge(
            [
                'app_date_format'=>"d.m.Y",
                'app_datetime_format'=>"d.m.Y H:i",
                'app_lang'=>"UA",
            ],
            $this->select("name", "value")->where("name","=",1)->get()->pluck("value",'name')->toArray()
        ); 
    }

    private function setCurrentAcademicPeriod(int $yearStart = null){
        
        $app_academic_year = json_decode($this->app_defaults['app_acdemic_year'] ?? '{}', true);
        
        if(is_null($yearStart))
            $yearStart = date('n')< 9 ? date("Y")-1 : date("Y");

        $yearEnd = $yearStart+1;

        $data =[
            "start" => ["d"=>1,"m"=>9,"y"=>$yearStart,"timestamp"=>Carbon::createFromDate($yearStart,9,1)],
            "end" => ["d"=>31,"m"=>7,"y"=>$yearEnd,"timestamp"=>Carbon::createFromDate($yearStart,7,31)]
        ];

        $this->app_defaults['app_academic_year'] = array_replace_recursive($data,$app_academic_year);

    }

    public function appDateFormat()
    {
        return $this->app_defaults['app_date_format'];
    }

    public function academicPeriodStart(){
        return Carbon::createFromDate(
            $this->app_defaults['app_academic_year']['start']['y'],
            $this->app_defaults['app_academic_year']['start']['m'],
            $this->app_defaults['app_academic_year']['start']['d']
        );
    }

    public function academicPeriodEnd(){
        return Carbon::createFromDate(
            $this->app_defaults['app_academic_year']['end']['y'],
            $this->app_defaults['app_academic_year']['end']['m'],
            $this->app_defaults['app_academic_year']['end']['d']
        );
    }

    /**
     * Summary of getAcademicYear
     * @param string $value period, begin, end
     * @param string $format 
     * @return array|string
     */
    public function getAcademicYear(string $value="period")
    {
        $ay = self::appCurrentAcademicYear();
        switch ($value){
            case("begin"):
                return date(self::appDateFormat(), mktime(0,0,0,9,1,$ay));
            // break;
            case("end"):
                return date(self::appDateFormat(), mktime(0,0,0,9,1,$ay));
            // break;
            default:
                return ["begin"=>"", "end"=>""];
        }
    }

    public function calculateStudyBegin(): Carbon
    {
        return Carbon::now()->month < 9 
            ? Carbon::create(Carbon::now()->year - 1, 9, 1) 
            : Carbon::create(Carbon::now()->year, 9, 1);
    }

    public static function getValue(string $name)
    {
        return self::where("name","=",$name)->orderByDesc("created_at")->value('value') ?? null;
    }
    
}
