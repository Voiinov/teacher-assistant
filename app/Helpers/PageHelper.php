<?php

namespace App\Helpers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class PageHelper extends ServiceProvider {

    public $dateFormat = "d.m.Y";
    public $dateTimeFormat = "d.m.Y H:i";
    public $timeFormat = "H:i";

    public function boot()
    {
        view()->share("PageHelper",$this);
    }
    
    public function addCSS(string $asset)
    {
        $func = mb_strtolower($asset);
        echo $this->constructCSS($this->$func()) ?? NULL;
    }

    public function addJS(string $asset)
    {
        $func = mb_strtolower($asset);
        echo $this->constructJS($this->$func()) ?? NULL;
    }

    private function constructCSS(array $data)
    {
        $string = "\n<!-- " . $data['description'] . " -->";
        foreach ($data['css'] as $path) {
            $string .= "\n<link rel=\"stylesheet\" href=\"" . asset( $path) ."\">";
        }
        return $string;
    }

    private function constructJS(array $data)
    {
        $string = "\n<!-- " . $data['description'] . " -->";
        foreach ($data['js'] as $path) {
            $string .= "\n<script src=\"" . asset( $path) ."\"></script>";
        }
        return $string;
    }

    public function date(string $date){
        return date($this->dateFormat,strtotime($date));
    }

    public function dateTime(string $date){
        return date($this->dateTimeFormat,strtotime($date));
    }

    public function time(string $date){
        return date($this->timeFormat,strtotime($date));
    }

    public function eventDate(string $date){
        return date("d.m.Y H:i",strtotime($date));
    }

    public function getUserAvatarPath(int $userID){
        
        // $ava = self::users()->select("avatar")->where("id","=",$userID)->limit(1)->get()->toArray();
        $ava = User::select("avatar")->where("id","=",$userID)->first()->get()->toArray();
        // $path = url(Storage::url("img/avatars/" . $ava[0]['avatar']));
        return isset($ava[0]['avatar']) ? Storage::url("img/avatars/" . $ava[0]['avatar']) : Storage::url("img/avatars/default.jpg");
        
    }


    private function inputmask(array $options = null)
    {
        return[
            "description"=>"InputMask",
            "js" => 
                [
                    "plugins/moment/moment.min.js",
                    "plugins/inputmask/jquery.inputmask.min.js"
                ],
        ];

    }
    private function icheck(array $options = null)
    {
        return[
            "description"=>"icheck bootstrap",
            "css" => 
                [
                    "plugins/icheck-bootstrap/icheck-bootstrap.min.css",
                ],
        ];

    }

    private function select(array $options = null)
    {
        return[
            "description"=>"Select2",
            "css" => [
                    "plugins/select2/css/select2.min.css",
                    "plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css",
                ],
            "js" =>[
                    "plugins/select2/js/select2.full.min.js",
                ]
        ];
    }
    
    private function summernote(array $options = null)
    {
        return[
            "description"=>"summernote",
            "css" => [
                    "plugins/summernote/summernote-bs4.min.css",
                ],
            "js" =>[
                    "plugins/summernote/summernote-bs4.min.js",
                ]
        ];
    }

    private function datatables(array $options = null){
        return[
            "description"=>"DataTables",
            "css"=>[
                "plugins/datatables-bs4/css/dataTables.bootstrap4.min.css",
                "plugins/datatables-responsive/css/responsive.bootstrap4.min.css",
                "plugins/datatables-buttons/css/buttons.bootstrap4.min.css",
            ],
            "js"=>[
                "plugins/datatables/jquery.dataTables.min.js",
                "plugins/datatables-bs4/js/dataTables.bootstrap4.min.js",
                "plugins/datatables-responsive/js/dataTables.responsive.min.js",
                "plugins/datatables-responsive/js/responsive.bootstrap4.min.js",
                "plugins/datatables-buttons/js/dataTables.buttons.min.js",
                "plugins/datatables-buttons/js/buttons.bootstrap4.min.js",
                "plugins/datatables-buttons/js/buttons.html5.min.js",
                "plugins/datatables-buttons/js/buttons.print.min.js",
                "plugins/datatables-buttons/js/buttons.colVis.min.js",
            ],
        ];
    }

    private function jszip(array $options = null){
        return[
            "description"=>"jszip",
            "js"=>["plugins/jszip/jszip.min.js"],
        ];
    }
    private function pdfmake(array $options = null){
        return[
            "description"=>"pdfmake",
            "js"=>[
                "plugins/pdfmake/pdfmake.min.js",
                "plugins/pdfmake/vfs_fonts.js"
            ],
        ];
    }
    
}
