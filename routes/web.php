<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{DashboardController}; 
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/' , [DashboardController::class , 'getAdminDashboard'])->name('get.admin.dashboard');
Route::get('/client-list' , [DashboardController::class , 'getAdminDashboard'])->name('get.client.list');
Route::get('/editor-list' , [DashboardController::class , 'getAdminDashboard'])->name('get.editor.list');
Route::get('/folder-list' , [DashboardController::class , 'getAdminDashboard'])->name('get.folder.list');

// Route::get('/nouman-home' , [DashboardController::class , 'getAdminDashboard'])->name('get.admin.dashboard');