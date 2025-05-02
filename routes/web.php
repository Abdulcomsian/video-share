<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{BillingController, DashboardController, UserController , FolderController, HomeController, ProfileController};
use Illuminate\Http\Request;
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


Route::middleware(['auth:web' , 'web.admin.verify'])->group(function(){
    Route::get('/' , [DashboardController::class , 'getAdminDashboard'])->name('get.admin.dashboard');
    Route::group(['prefix' => 'admin', 'as' => 'admin:'], function () {

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

        // profile
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        Route::put('password', [ProfileController::class, 'updatePassword'])->name('password.update');
    });

});

Route::get('test' , function(){
    \Storage::disk('s3')->makeDirectory("Hello-Mani2");
    dd("folder created successfully");
});


Route::view('test-data' , 'test' );
Route::post('test-data' , function(Request $request){
    dd($request->all());
    foreach($request->data  as $data){
        $index = 0;
        foreach($data['file'] as $file){
            $index++;
        }
        dd($index);
    }
})->name('test-data');

// Route::get("test-update-link" , [UserController::class ,'updateLinkPage']);
