<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ AuthController ,  UserController , FolderController , JobController};
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::match(['GET', 'POST'], '/login', [AuthController::class, 'login'])->name('login');
Route::post('register' ,[AuthController::class , 'register']);
Route::post('verify',[AuthController::class , 'verifyUser']);

/* Editor Routes*/
Route::middleware(['auth:api' , 'api.editor.verify'])->group(function(){
    Route::post('update-editor-profile' , [UserController::class , 'updateEditorProfile']);
    Route::post('update-editor-portfolio' , [UserController::class , 'updateEditorPortfolio']);
    Route::post('update-editor-education' , [UserController::class , 'updateEditorEducation']);
    Route::post('update-hourly-rate' , [UserController::class , 'updateHourlyRate']);
    Route::post('update-address' , [UserController::class, 'updateAddress']);
    Route::post('update-biography' , [UserController::class ,'updateBiography']);
    Route::post('update-language' , [UserController::class , 'updateLanguage']);
    Route::post('delete-skill' , [UserController::class , 'deleteSkill']);
    Route::post('add-editor-skill' , [UserController::class ,'addEditorSkill']);
    Route::post('update-education' , [UserController::class ,'updateEducation']);
    Route::post('add-job-request' , [JobController::class , 'addJobRequest']);
    Route::post('logout' , [AuthController::class , 'logout']);
});


/* Client Routes*/
Route::middleware(['auth:api' , 'api.client.verify'])->group(function(){
    Route::post("create-folder" , [FolderController::class , 'createClientFolder']);
    Route::post("post-job" , [JobController::class , 'addJob']);
    Route::get("client-jobs" , [JobController::class , 'clientJob']);
    Route::get("request-list" , [JobController::class , 'requestList']);
    Route::post("award-job" , [JobController::class , "awardJob"]);
    Route::get("awarded-job-list", [JobController::class ,"awardedJobList"]);
    Route::post("add-favourite" , [UserController::class , 'addFavourite']);
    // Route::post("view-folderfiles" , [ViewFolderFiles::class , 'viewfolderfiles']);

    Route::post('/filesclientfolder', [FolderController::class, 'filesClientFolder']);
});
