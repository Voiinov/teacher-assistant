<?php

use App\Http\Controllers\MinuteBookController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\GradebookController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\GradebookGradesController;

Route::get('/', function () {
    return view('welcome');
});

Route::get("/p",[\App\Http\Controllers\PublicController::class,"index"])->name("public");
Route::get("/api",[\App\Http\Controllers\ApiController::class,"api"])->name("api");

Auth::routes();

Route::middleware(['auth', 'verified','access','actionslogger'])->group(function () {

    // Route::get('{route}/404',[Controller::class,"error403"])->name("error403");
    // Route::get('{path}/{code}',[Controller::class,"errorPage"])->name("error.page");

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    // Users
    Route::resource('users', UsersController::class);
    Route::get("/profile",[UsersController::class,"profile"])->name("users.profile");

    Route::resource('student', StudentController::class);
    Route::post('/student/ajax', [StudentController::class,"ajax"])->name("students.datatable");
    
    /***************Groups*************/
    Route::resource('groups', GroupsController::class);

    Route::resource('minutebook', MinuteBookController::class);

    Route::resource('subject', SubjectController::class);


    /***************Curriculum mapping*************/
    Route::resource('curriculum', CurriculumController::class);
    Route::prefix('curriculum/{subjectId}/mapping')->name('curriculum.mapping.')->group(function () {
        Route::get('/', [CurriculumController::class, "indexCurriculumMapping"])->name("index");
        Route::get('/create', [CurriculumController::class, "createCurriculumMapping"])->name("create");
        Route::post('/store', [CurriculumController::class, "storeCurriculumMapping"])->name("store");
        Route::get('/edit/{mappingId}', [CurriculumController::class, "editCurriculumMapping"])->name("edit");
        Route::get('/{mappingId}', [CurriculumController::class, "showCurriculumMapping"])->name("show");
        Route::post('/{mappingId}/update/ajax', [CurriculumController::class, "ajaxUpdateCurriculumMapping"])->name("update.ajax");
        // Route::post('/{mappingId}/update', [CurriculumController::class, "ajaxUpdateCurriculumMapping"])->name("update");
    });

    /***************Curriculum themes *************/
    Route::get('/curriculum/themes/create', [CurriculumController::class,'createTheme'])->name('curriculum.themes.create');
    Route::get('/curriculum/{subjectId}/themes', [CurriculumController::class,'indexTheme'])->name('curriculum.themes.index');
    Route::get('/curriculum/themes/show', [CurriculumController::class,'show'])->name('curriculum');
    Route::post('/curriculum/themes/store', [CurriculumController::class,'storeTheme'])->name('curriculum.themes.store');
    Route::get('/curriculum/themes/edit/{themeId}', [CurriculumController::class,'editTheme'])->name('curriculum.themes.edit');
    Route::get('/curriculum/themes/show/{themeId}', [CurriculumController::class,'showTheme'])->name('curriculum.themes.show');
    Route::post('/curriculum/themes/update/{themeId}', [CurriculumController::class,'updateTheme'])->name('curriculum.themes.update');

    /***************Timetable *************/
    Route::get('/timetable/import', [TimetableController::class,'indexImport'])->name("timetable.import");
    Route::get('/timetable/import/ajax', [TimetableController::class,'importDataAjax'])->name("timetable.import.ajax");
    Route::post('/timetable/import/store/ajax', [TimetableController::class,'storeImportDataAjax'])->name("timetable.import.store.ajax");
    Route::get('/timetable/index/ajax', [TimetableController::class,'indexAjax'])->name("timetable.index.ajax");
    Route::resource('timetable', TimetableController::class)->only(['index']);

    /**************Grade book************/
    Route::get("/gradebook/group/{groupID}",[GradebookController::class,"groupGradeBooks"])->name("gradebooks.group");
    Route::resource('gradebook',GradebookController::class);
    /** Grades */
    Route::get("/gradebook/group/subject/grades/ajax",[GradebookGradesController::class,"groupSubjectGradesAjax"])->name("gradebooks.group.subject.grades.ajax");
    Route::get("/gradebook/grade/{lessonID}",[GradebookGradesController::class,"create"])->name("gradebooks.grade.create");
    Route::get("/gradebook/grade/saveupdate/ajax",[GradebookGradesController::class,"gradeSaveUpdateAjax"])->name("gradebook.grade.saveupdate.ajax");
    Route::get("/gradebook/grade/type/saveupdate/ajax",[GradebookGradesController::class,"gradeSaveUpdateTypeAjax"])->name("gradebook.grade.type.saveupdate.ajax");

});
