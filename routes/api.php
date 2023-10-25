<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ AuthController ,  UserController , FolderController , JobController , BillingController , FileController , FavouriteRequestController};
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
Route::post('verify-code',[AuthController::class , 'verifyUser']);
Route::post('logout' , [AuthController::class , 'logout']);

Route::middleware(['auth:api'])->group(function(){
    Route::post('update-profile-image' , [UserController::class , 'updateProfileImage']);
});

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
    Route::get('profile-detail' , [UserController::class , 'getProfileDetail']);
    Route::get('proposal-list' , [JobController::class , 'getProposalList']);
    Route::get('job-list' , [JobController::class , 'getJobList']);
    Route::get('unassigned-job-list' , [JobController::class , 'getUnassignedJobs']);
    Route::post('share-files' , [FolderController::class , 'shareFile']);
    Route::post('getshares' , [FolderController::class , 'getshares']);
    Route::post('delete-share-file' , [FileController::class , 'deleteShareFile']);
    Route::get('cancel-jobs' , [JobController::class , 'cancelJobs']);
    Route::get('done-jobs' , [JobController::class , 'doneJobs']);
    Route::post('job-folder' , [FolderController::class , 'getJobFolder']);
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
    Route::get('favourite-list' , [UserController::class , 'getFavouriteList']);
    Route::post('add-folder-file', [FolderController::class, 'addFolderFile']);
    Route::post('job-detail', [JobController::class, 'getJobDetail']);
    Route::get('editor-list' , [UserController::class , 'getEditorList']);
    Route::post('pay-bill' , [BillingController::class , 'payBill']);
    Route::get('folder-list' , [FolderController::class , 'getClientFolders']);
    Route::post('folder-detail' , [FolderController::class , 'getFolderDetail']);
    Route::post('upload-files' , [FileController::class , 'uploadClientFile']);
    Route::post('delete-file' , [FileController::class , 'deleteClientFile']);
    Route::post('update-folder' , [FolderController::class, 'updateClientFolder']);
    Route::post('delete-folder' , [FolderController::class , 'deleteClientFolder']);
    Route::post('get-folder-files' , [FolderController::class , 'getFolderFiles']);
    Route::post('client-share-folder-files' , [FolderController::class , 'getShareFiles']);
    Route::post('process-payment', [BillingController::class , 'processBilling']);
    Route::get('public-key' , [BillingController::class , 'getPublicKey']);
    Route::post('get-payment-intent' , [BillingController::class , 'getPaymentIntent']);
    Route::post('complete-job' , [JobController::class , 'doneAwardedJob']);
    Route::post('cancel-job' , [JobController::class , 'cancelAwardedJob']);
    //new apis starts here
    Route::post('add-favourite-request' , [FavouriteRequestController::class , 'addFavouriteRequest']);
    Route::post('get-favourite-request' , [FavouriteRequestController::class , 'getFavouriteRequest']);
    Route::post('delete-favourite-request' , [FavouriteRequestController::class , 'deleteFavouriteRequest']);
    Route::get('posted-jobs' , [JobController::class , 'postJobList']);
    Route::get('awarded-jobs' , [JobController::class , 'awardJobList']);
    Route::post('job-request-list' , [JobController::class , 'jobRequestList']);
    Route::post('award-job-request' , [JobController::class , 'awardedJobRequest']);
});


