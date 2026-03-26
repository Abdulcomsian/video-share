<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{BillingController, DashboardController, UserController, FolderController, HomeController, ProfileController, EditorOnboardingController};
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

// stripe onboarding
Route::get('stripe/onboarding/success', [EditorOnboardingController::class, 'onBoardingSuccess'])->name('stripe.onboarding.success');
Route::get('stripe/onboarding/error', [EditorOnboardingController::class, 'onBoardingError'])->name('stripe.onboarding.error');
Route::get('/stripe/refresh', [EditorOnboardingController::class, 'refreshOnboarding'])->name('stripe.refresh');
Route::get('/stripe/success', [EditorOnboardingController::class, 'stripeSuccess'])->name('stripe.success');

Route::get('/terms-and-condition', [HomeController::class, 'termsAndConditionPage'])->name('get.termsAndCondition.page');
Route::get('/privacy-policy', [HomeController::class, 'privacyPolicyPage'])->name('get.privacyPolicy.page');
Route::get('/help', [HomeController::class, 'helpPage'])->name('get.help.page');

Auth::routes(['except' => ['register']]);


Route::middleware(['auth:web', 'web.admin.verify'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin:dashboard');
    });
    Route::group(['prefix' => 'admin', 'as' => 'admin:'], function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/manage/clients', [ClientController::class, 'index'])->name('clients.list');
        Route::get('/manage/editors', [EditorController::class, 'index'])->name('editors.list');
        Route::get('/jobs', [JobController::class, 'index'])->name('jobs.list');
        Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.show');
        Route::get('/jobs/proposals/{id}', [JobController::class, 'jobProposals'])->name('jobs.proposals-list');
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


