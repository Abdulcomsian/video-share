<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{BillingController, DashboardController, UserController , FolderController, HomeController, ProfileController};
use App\Http\Controllers\Web\{ClientController, EditorController, JobController};
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

Route::get('db/dld', function () {

    $database = env('DB_DATABASE');
    $username = env('DB_USERNAME');
    $password = env('DB_PASSWORD');
    $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
    $path = storage_path('app/' . $filename);

    $command = "mysqldump -u $username -p'$password' --databases $database > $path 2>&1";
    exec($command, $output, $returnVar);

    if ($returnVar !== 0) {
        dd("Error exporting database:", $output);
    }

    return response()->download($path);
});


Route::middleware(['auth:web' , 'web.admin.verify'])->group(function(){
    Route::get('/' , function (){
        return redirect()->route('admin:dashboard');
    });
    Route::group(['prefix' => 'admin', 'as' => 'admin:'], function () {
        Route::get('/dashboard' , [DashboardController::class , 'index'])->name('dashboard');
        Route::get('/manage/clients' , [ClientController::class , 'index'])->name('clients.list');
        Route::get('/manage/editors' , [EditorController::class , 'index'])->name('editors.list');
        Route::get('/jobs' , [JobController::class , 'index'])->name('jobs.list');
        Route::get('/jobs/{id}' , [JobController::class , 'show'])->name('jobs.show');
        Route::get('/jobs/proposals/{id}' , [JobController::class , 'jobProposals'])->name('jobs.proposals-list');
        // Route::get('/client-list' , [UserController::class , 'getDashboardClientList'])->name('get.client.list');
        // Route::get('/editors' , [DashboardController::class , 'getEditorPage'])->name('editor.page');
        // Route::get('/editor-list' , [UserController::class , 'getDashboardEditorList'])->name('get.editor.list');
        // Route::get('/folders' , [DashboardController::class , 'getFolderPage'])->name('folder.page');
        // Route::get('/folder-list' , [FolderController::class , 'getFolderList'])->name('get.folder.list');
        //test routes starts here
        // Route::get("billing" , [BillingController::class , 'getBillingPage']);
        // Route::post("add-billing" , [BillingController::class , 'processBillingFees'])->name('stripe.store');
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
