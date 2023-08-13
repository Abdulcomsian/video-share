<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{DashboardController, UserController , FolderController}; 
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
});
