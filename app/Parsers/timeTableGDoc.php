<?php

namespace App\Parsers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Options;
use App\Models\Subject;
use App\Models\User;
use App\Models\Groups;
use App\Models\Timetable;
use Illuminate\Support\Carbon;

use function PHPUnit\Framework\returnValue;

class timeTableGDoc{    
    
    private $storageFile;
    private $subjectsList = [];
    private $usersList = [];
    private $groupsList = [];
    public $year;
    public $errors = [
        'count'=>0,
        'descriptions'=>[],
    ];

    
    
    public function __construct(){
        $this->subjectsList = $this->getSujectList(true);
        $this->usersList = $this->getUserList(true);
    }
    
    /**
     * Get data in the specified format, defaulting to JSON.
     *
     * @param string $dataType The format of the returned data; defaults to JSON.
     * @return \Illuminate\Http\JsonResponse JSON response containing parsed CSV data or an error message.
     */
    public function getData(string $dataType = "json")
    {
    
        $urlOption = Options::getValue( "timetable_import_gdoc_csv_url");

        $csvData=[];
        
        if (!$urlOption) {
            return response()->json(['error' => 'CSV URL not found.'], 404);
        }
               
        $csvData = $this->openCSV($urlOption);

        if ($csvData === false) {
            return response()->json(['error' => 'Failed to open or parse CSV file.'], 500);
        }

        $count = isset($this->storageFile['data']) ? count($this->storageFile['data']) : 0;

        $this->storageFile['summary']['items'] = $count;
        $this->storageFile['summary']['id'] = $count;        
        Storage::disk('local')->put("temp/timetable.json", json_encode($this->storageFile, JSON_PRETTY_PRINT));

        return response()->json($csvData);
    }


    /**
     * Open a CSV file and parse its data into an array.
     *
     * @param string $url An string containing the CSV file path.
     * @return array|false Parsed CSV data as a multi-dimensional array, or false on failure.
     */
    private function openCSV($url)
    {
        if (($handle = fopen($url, "r")) !== false) {

            // Remove header row
            fgetcsv($handle);
            // get FirstYear

            // Get data in array format
            $data = ['data' => []];
            $rowNumber = 3;
    
            $lastDate = $this->lastDateLesson();

            if($row = fgetcsv($handle)) // first row
            {
                $date = $this->dateValue($this->dateStringPrepare($row[2]));
                
                $Y = $date->format("Y");

                $this->groupsList = (new Groups)->getSimpleActualGroupsList(true,intval($Y));
                // prepare date string
                $date = $this->dateStringPrepare($row[2]);
                // event date time bein
                $timestampStart = $this->dateValue($date, $row[8]);
                if( $this->actionDateCheck($lastDate, $timestampStart) ){
                    // event date time end
                    $timestampEnd = $this->dateValue($date, $row[9]);
                    $data['data'][] = $this->rowToArray($row, 2,0,$timestampStart,$timestampEnd);
                }
            }

    
            while ($row = fgetcsv($handle)) {
                
                if (!empty($row[0]) && !empty($row[3]) && !empty($row[2])) {
                    // prepare date string
                    $date = $this->dateStringPrepare($row[2]);
                    
                    // event date time bein
                    $timestampStart = $this->dateValue($date, $row[8]);
    
                    if( $this->actionDateCheck($lastDate, $timestampStart) ){
                        // event date time end
                        $timestampEnd = $this->dateValue($date, $row[9]);
        
                        //substitute
                        if( $row[10] != "" ){
                            
                            $data['data'][] = $this->rowToArray($row, $rowNumber,1,$timestampStart,$timestampEnd);
                            
                            $substitute = array_merge($row, [3=>$row[10],4=>$row[10]]);
        
                            $data['data'][] = $this->rowToArray($substitute, $rowNumber,2,$timestampStart,$timestampEnd);    
                        }else{
                            $data['data'][] = $this->rowToArray($row, $rowNumber,0,$timestampStart,$timestampEnd);      
                        }
                    }
        
                }
                $rowNumber++;
            }
            return $data;
        }
        return false;
    }

    public function lastDateLesson()
    {
        $data = Timetable::select("begin")->orderByDesc('begin')->get()->first();
        if($data){
            $date = $data->toArray();
            return Carbon::parse($date['begin']);
        }else{
            return Carbon::createFromDate(1985,4,24);
        }
    }

    /**
     * Construct an array item with display, database, and sort values for each column.
     *
     * @param array $row Array representing a row of data.
     * @param int|null $rowNumber Optional row number for display purposes.
     * @return array Array formatted with display, database, and sort values for each column.
     */
    private function rowToArray(array $row, $rowNumber = null, int $substitute, ...$timestamp)
    {
        $timestampStart = $timestamp[0];
        $timestampEnd = $timestamp[1];
        //unique row number
        $r = intval($rowNumber.$substitute);

        // get suject ID
        $subjectID = $this->getSubjectId($row[3], $r);
        
        // get user ID
        $userID = $subjectID == 16 ? NULL : $this->getUserId($row[4], $r);
        
        // get group ID
        $groupID = $this->getGroupId($row[0], $r);
        
        // counstruct unique item ID
        $itemID = $this->constructRowID($row[1], $groupID, $timestampStart,$substitute);
        
        $errorsInRow = isset($this->errors['descriptions'][$r]) ? count($this->errors['descriptions'][$r]) : 0;
        
        if($errorsInRow>0)
            $errorMsgRow = "<button class='btn btn-block btn-danger btn-sm importErr'><i class='fas fa-exclamation-triangle'></i> <span class='badge badge-light'>{$errorsInRow}</span></button>";
        else
            $errorMsgRow = "<i class='fas fa-check text-success'></i>";

        $row=[
            $this->createRow(null,  $timestampStart->isoFormat("Y-m-d"), $timestampStart->isoFormat("Ymd"), 'string'),
            $this->createRow($groupID, $row[0],  $row[0], 'string'),
            $this->createRow($subjectID,$row[3], $row[3], 'string',),
            $this->createRow($userID,$row[4],  $row[4], 'string'),
            $this->createRow(null,$row[8], $row[8], 'string'),
            $this->createRow(null,$row[9], $row[9], 'string'),
            $this->createRow(null,$row[1], $row[1],  'num'),
            $this->createRow(null,$rowNumber, $itemID,  'num'),
            $this->createRow(null,$errorMsgRow,  "$errorsInRow", 'num'),
        ];

        $this->storageFile['data'][] = [ 
            'id' => $itemID,
            'group_id' => $groupID,
            'subject_id' => $subjectID,
            'user_id' => $userID,
            'begin' => $timestampStart->format("Y-m-d H:i:s"),
            'end' => $timestampEnd->format("Y-m-d H:i:s"),
            'substitute' => $substitute,
            'created_at' => $timestampStart->format("Y-m-d H:i:s"),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->storageFile['id'][$itemID][] = $rowNumber;
        return $row;

    }

    private function createRow($error, $display, $sort, $type)
    {
        return [
            'display' => $display,
            'error' => $error,
            'sort'    => $sort,
            'type'    => $type,
        ];
    }

    public function actionDateCheck($lastDate, $timestampStart){
        return $lastDate->lessThan($timestampStart);
        // return true;
    }

    /**
     * Construct a unique row ID based on lesson, group ID, and timestamp.
     *
     * @param string $lesson The lesson identifier, expected to be a string that can be padded.
     * @param int $groupID The unique identifier for the group.
     * @param carbon $timestamp The timestamp used to generate the ID (should represent a valid date).
     * @return string The constructed unique row ID.
     */
    private function constructRowID($lesson, $groupID, $timestamp, $substitute = 0): string
    {

        // Format the year and week number from the timestamp
        $year = $timestamp->format("y"); // Two-digit year
        $weekNumber = str_pad($timestamp->dayOfYear, 3, "0", STR_PAD_LEFT); // Week number padded to 2 digits
        
        // Pad the lesson ID to ensure it is two characters long
        $paddedLesson = str_pad($lesson, 2, "0", STR_PAD_LEFT);

        // Construct and return the unique row ID
        return intval($year . $weekNumber . $groupID . $substitute . $paddedLesson);
    }

    /**
     * Prepare date string by splitting it into an array and returning the second element if available.
     *
     * @param string $date A date string to split by comma and space.
     * @return string The second part of the date string if available, otherwise the original string.
     */
    private function dateStringPrepare(string $date): string {
        $d = explode(", ", $date);
        return $d[1] ?? $date;
    }

    /**
     * Convert a date string and optional time string to a Unix timestamp.
     *
     * @param string $date The date string in a valid format (e.g., "d.m.Y").
     * @param string|null $time Optional time string (e.g., "H:i:s").
     * @return Carbon
     */
    private function dateValue(string $date, string $time = null) {
        $date .= is_null($time) ? " 00:00:00" : " {$time}";
        return Carbon::parse($date);
    }

    /**
     * Return an array of subjects where the subject ID is the array key.
     * If $reverse is TRUE, then the subject name will be the array key instead.
     * @param bool $reverce
     * @return array[]
     */
    private function getSujectList(bool $reverce = false)
    {
        
        $list = Subject::select("id","title");
        return $reverce ? $list->pluck("id","title")->toArray() : $list->pluck("title","id")->toArray();
       
    }

    /**
     * Return an array of users where the user ID is the array key.
     * If $reverse is TRUE, then the user name will be the array key instead.
     * @param bool $reverce
     * @return array[]
     */
    private function getUserList(bool $reverce = false)
    {
        $list = User::select("id","name");
        return $reverce ? $list->pluck("id","name")->toArray() : $list->pluck("name","id")->toArray();
    }

   
    private function getGroupId(string $name, int $row): string
    {
            
        if (isset($this->groupsList[$name])) {
            return intval($this->groupsList[$name]);
        }

        // Increment error count and add an error message when user is not found
        $this->errorLog("User :name not found!",$row,$name);
        return "error";
    }

    private function getUserId(string $name, int $row)
    {
        
        if($name=="" || is_null($name))
        {
            return null;
        }

        $name = trim($name);

        if (isset($this->usersList[$name])) {
            return $this->usersList[$name];
        }

        // Increment error count and add an error message when user is not found
        $this->errorLog("User :name not found!",$row,$name);
        return "error";
    }
    /**
     * Retrieve the subject ID by subject name.
     *
     * @param string $subjectName The name of the subject to search for.
     * @return string|int Returns the subject ID if found, otherwise returns 'error'.
     */
    private function getSubjectId(string $name, int $row)
    {
        
        $name = trim($name);

        if (isset($this->subjectsList[$name])) {
            return $this->subjectsList[$name];
        }
        
        // Increment error count and add an error message when subject is not found
        $this->errorLog(__("Subject :name not found!",["name"=>$name]),$row,$name);

        return "error";
    }

    private function errorLog(string $err,int $key, string $text, string $type = null)
    {
        $this->errors["count"]++;
        $this->errors['descriptions'][$key][] = [
            "error" => $err,
            "name" => $text,
        ];
    }

}