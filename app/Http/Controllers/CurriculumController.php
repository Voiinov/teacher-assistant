<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Curriculum;
use App\Models\CurriculumTheme;
use App\Models\Subject;
use App\Models\CurriculumMapping;
use App\Http\Controllers\ActionsController;
use Illuminate\Support\Str;

class CurriculumController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->get('page') == "themes")
            return view("education.curriculum.themes.index",[
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Subject $subjectController)
    {
        return view("education.curriculum.create",[
            "pageTitle"=>"Curriculum mapping",
            "subjectId"=>$request->subjectId ?? NULL,
            "subjectsList"=>$subjectController->select("id","title")->where('active',1)->orderBy('title',"asc")->get()->toArray(),
            
        ]);
    }


    //******************* */
    // Curriculum Mapping (CM)
    //******************* */
    
    private function getCurriculumMapping($mappingId){

        $data = CurriculumMapping::with(["themes","subject"])->where("id","=",$mappingId)->first();
        
        // $themes = $data->themes->keyBy('lesson');

        // $list = collect(range(1, $data->hours));

        $list = collect(range(1, $data->hours))->mapWithKeys(function ($lesson) {
            return [$lesson => collect()];
        });
        
        // Додаємо кожну тему до відповідного уроку
        $data->themes->each(function ($item) use ($list) {
            $lesson = abs($item->pivot->lesson);  // Абсолютне значення lesson
            $list[$lesson]->put($item->pivot->lesson,$item);           // Додаємо тему до відповідного уроку
        });
        return $data->setRelation("themes",$list);

    }

    public function indexCurriculumMapping(Request $request, Curriculum $curriculum){

        return view("education.curriculum.mapping.index",[
            "pageTitle"=>"Curriculum mapping",
            "breadcrumb"=>[
                ['name'=>'Curriculum',"url"=>route('curriculum.index')],
                ['name'=>'CM','current'=>true],
            ],
            "subjectId"=>$request->subjectId,
            "list"=>$curriculum->getMappingList($request->subjectId)->toArray(),
            
        ]);
    }

    public function showCurriculumMapping(Request $request){

        return view("education.curriculum.mapping.show",[
            "pageTitle"=>__("Curriculum mapping"),
            "breadcrumb"=>[
                ['name'=>'Curriculum',"url"=>route('curriculum.index')],
                // ['name'=>'Themes',"url"=>route('curriculum.themes.index',$request->mappingId)],
                ['name'=>'Show','current'=>true]
            ],
            "data" => $this->getCurriculumMapping($request->mappingId)
        ]);
    }

    public function createCurriculumMapping(Request $request, Subject $subject){
        return view("education.curriculum.mapping.create",[
            "pageTitle"=>"Curriculum mapping",
            "breadcrumb"=>[
                ['name'=>'Curriculum',"url"=>route('curriculum.index')],
                ['name'=>'Themes',"url"=>route('curriculum.themes.index',$request->subjectId)],
                ['name'=>'Create','current'=>true]
            ],
            "subjectId"=>$request->subjectId ?? NULL,
            "subjectsList"=>$subject->select("id","title")->where('active',1)->orderBy('title',"asc")->get(),         
        ]);
    }

    public function editCurriculumMapping(Request $request, Curriculum $curriculum)  {
        
        $data = $this->getCurriculumMapping($request->mappingId);

        return view("education.curriculum.mapping.edit",[
            "pageTitle"=>"Curriculum mapping",
            "subjectId"=>$request->subjectId,
            "mappingId"=>$request->mappingId,
            "data" => $data,
            "themesList"=>$curriculum->getThemesList($request->subjectId)->groupBy("module")->toArray(),
            "themesLessonList"=>$data->themes->groupBy("lesson")->toArray(),
            "breadcrumb"=>[
                    ['name'=>'Curriculum',"url"=>route('curriculum.index')],
                    ['name'=>'CM',"url"=>route('curriculum.mapping.index',$request->subjectId)],
                    ['name'=>'Edit','current'=>true]
                ],
            
            ]
        );
    }

    /**
     * Summary of storeCurriculumMapping
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Curriculum $curriculum
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeCurriculumMapping(Request $request, Curriculum $curriculum)  {
        $data = $request->validate([
            "title" => ["required","string"],
            "subject_id" => ["required","integer"],
            "description" => ["nullable","string"],
            "hours" => ["required","integer","min:5","max:500"],
        ]);
        
        $data['user_id'] = Auth::id();
        $data['created_at'] = now();

        try{
            $mappingId = $curriculum->storeMapping($data);
        }catch(\Illuminate\Database\QueryException $e){
            return back()->withErrors(['errors' => $e->getMessage()])
                        ->withInput();
        }
        
        return to_route('curriculum.mapping.index', $data['subject_id']);

    }

    /**
     * Summary of ajaxUpdateCurriculumMapping
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Curriculum $curriculum
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function ajaxUpdateCurriculumMapping(Request $request, CurriculumTheme $curriculumTheme)
    {

        $result = $curriculumTheme->storeUpdateThemeConn($request->mappingId, $request->themeId, $request->lesson);

        // return response()->json(array_filter($result))->setStatusCode(201);
        return response()->json($result)->setStatusCode(201);
        
    }

    // THEMES
    /**
     * Summary of createTheme
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Subject $subject
     * @param \App\Models\Curriculum $curriculum
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function createTheme(Request $request, Subject $subject, Curriculum $curriculum)
    {
        
        $subjectID = $request->get('subject') ?? NULL;
        
        $grouped = $request->get('grouped') ?? NULL;

        return view("education.curriculum.themes.create",[
                "pageTitle"=>"Add theme",
                "breadcrumbs"=>[
                    ['name'=>'Curriculum',"url"=>route('curriculum.index')],
                    ['name'=>'Themes',"url"=>route('curriculum.themes.index',$subjectID)],
                    ['name'=>'Create','current'=>true],
                ],
                "subjectId"=>$subjectID,
                "modules"=>$curriculum->getModulesList($subjectID),
                "grouped"=>$grouped,
                "subjectsList"=>$subject->select("id","title")->where('active',1)->orderBy('title',"asc")->get(),
            ]);
    }
   
    public function indexTheme(Request $request, Subject $subject)
    {

        $themes = [];

        $data = $subject->getSubjectDataWithRelations($request->subjectId)->first();
    
        if(isset($data->options) && $data->options !="" ){
            $data->options = json_decode($data->options,true);
        }else{
            $data->options = [
                'description'=>""
            ];
        }

        if(count($data->themes) > 0){
            $themes = $data->themes->groupBy("grouped")->toArray();
        }

        $data = $data->toArray();
        
        $data['themes']= $themes;

        return view("education.curriculum.themes.index",[
                "pageTitle"=>"Themes catalogue",
                "breadcrumbs"=>[
                    ['name'=>'Curriculum',"url"=>route('curriculum.index')],
                    ['name'=>'Themes','current'=>true],
                ],
                "subjectId"=>$request->subjectId ?? NULL,
                "data"=>$data,
            ]);
    }

    public function editTheme(Request $request, Curriculum $curriculum, CurriculumTheme $themes){

        $theme = $themes->getTheme($request->themeId);

        return view("education.curriculum.themes.edit",[
            "pageTitle"=>"Edit theme",
            "breadcrumbs"=>[
                ['name'=>'Curriculum',"url"=>route('curriculum.index')],
                ['name'=>'Themes',"url"=>route('curriculum.themes.index',$theme->subject)],
                ['name'=>'Edit','current'=>true],
            ],
            "data"=>$theme->getTheme($request->themeId),
            "subjectId"=>$theme->subject,
            "themeId"=>$request->themeId,
            "modules"=>$curriculum->getModulesList($theme->subject),
        ]);
    }


    public function showTheme(Request $request, Curriculum $curriculum, CurriculumTheme $themes){
        $theme = $themes->getTheme($request->themeId);
        return view("education.curriculum.themes.show",[
            "pageTitle"=>"Theme",
            "breadcrumbs"=>[
                ['name'=>'Curriculum',"url"=>route('curriculum.index')],
                ['name'=>'Themes',"url"=>route('curriculum.themes.index',$theme->subject)],
                ['name'=>'Edit','current'=>true],
            ],
            "data"=>$theme->getTheme($request->themeId),
            "themeId"=>$request->themeId,
            "modules"=>$curriculum->getModulesList($theme->subject),
        ]);
    }

    public function updateTheme(Request $request, CurriculumTheme $themes){


        if($themes->authorId($request->themeId)->user_id != Auth::id()){
            return back()->with('errors', collect([__('You are not allowed to edit this theme')]));
        }
        
        $data = $request->validate([
            "title" => "required|string|max:255|unique:curriculum_themes,title,NULL,id,subject," . $request->subject,
            "description"=>["nullable","string"],
            "grouped"=>["nullable","integer"],
            // "module"=>["nullable","integer"]
            ]
        );
    
        $data['updated_at'] = now();
    
        try{
            $result = $themes->updateTheme($data, $request->themeId);
        }catch(\Illuminate\Database\QueryException $e){
            return back()->with('errors', $e->getMessage());
        }
        return $result ? 
            to_route('curriculum.themes.index', ["subjectId"=>$request->subject])
            ->with("success",collect(["Changes saved successfully"]))
            : back();

    }

    public function storeTheme(Request $request,Curriculum $curriculum){

        $data = $request->validate([
            "title" => "required|string|max:255|unique:curriculum_themes,title,NULL,id,subject," . $request->subject,
            "description"=>["nullable","string"],
            "grouped"=>["nullable","integer"],
            "module"=>["nullable","integer"],
            "subject" => ["required","integer"],
            ]
        );
        $data['user_id'] = Auth::id();
        $data['created_at'] = now();
    
        try{
            $id = $curriculum->storeTheme($data);
        }catch(\Illuminate\Database\QueryException $exception){
            return back()->with('errors', $exception->getMessage());
        }

        (new ActionsController)->curriculum_themes_store_with_id($id, $data['subject']);

        return to_route('curriculum.themes.index', $data['subject']);
        
    }

    public function store(Request $request,Curriculum $curriculum)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Curriculum $curriculum)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Curriculum $curriculum)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Curriculum $curriculum)
    {
        //
    }

}
