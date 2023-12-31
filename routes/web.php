<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{BillingController, DashboardController, UserController , FolderController, HomeController}; 
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/terms-and-condition' , [HomeController::class , 'termsAndConditionPage'])->name('get.temsAndCondition.page');

Auth::routes(['except' => ['register']]);
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware([ 'auth' , 'web.admin.verify'])->group(function(){
    Route::get('/' , [DashboardController::class , 'getAdminDashboard'])->name('get.admin.dashboard');
    Route::get('/clients' , [DashboardController::class , 'getClientPage'])->name('client.page');
    Route::get('/client-list' , [UserController::class , 'getDashboardClientList'])->name('get.client.list');
    Route::get('/editors' , [DashboardController::class , 'getEditorPage'])->name('editor.page');
    Route::get('/editor-list' , [UserController::class , 'getDashboardEditorList'])->name('get.editor.list');
    Route::get('/folders' , [DashboardController::class , 'getFolderPage'])->name('folder.page');
    Route::get('/folder-list' , [FolderController::class , 'getFolderList'])->name('get.folder.list');
    //test routes starts here
    Route::get("billing" , [BillingController::class , 'getBillingPage']);
    Route::post("add-billing" , [BillingController::class , 'processBillingFees'])->name('stripe.store');
    //test routes ends here
});

Route::get('test' , function(){
    \Storage::disk('s3')->makeDirectory("Hello-Mani2");
    dd("folder created successfully");
});


// Route::get("test-update-link" , [UserController::class ,'updateLinkPage']);