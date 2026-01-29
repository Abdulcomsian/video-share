<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{ AuthController ,
                            UserController ,
                            FolderController ,
                            JobController ,
                            BillingController ,
                            CommentController,
                            FileController ,
                            FavouriteRequestController ,
                            HomeController,
                            ReviewController,
                            PersonalJobChatController,
                        };
use App\Http\Controllers\Stripe\{ClientController as StripeClientController , EditorController as StripeEditorController};
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
Route::post('forget-password' , [UserController::class , 'forgetPassword']);
Route::post('update-password' , [UserController::class , 'updatePassword']);
Route::post('resend-passcode' , [UserController::class , 'sendPasscode']);

Route::middleware(['verify.authentication'])->group(function(){
    Route::get('get/google-location-api/token', function () {
       return response()->json(['success' => true , '_token' => config('app.google_location_api_token')]);
    });
    Route::post('update-profile-image' , [UserController::class , 'updateProfileImage']);
    Route::post('job-detail', [JobController::class, 'getJobDetail']);
    Route::post('add-file-comment', [CommentController::class, 'addFileComment']);
    Route::post('add-share-file-comment', [CommentController::class, 'addShareFileComment']);
    Route::post('get-file-detail' , [FileController::class , 'getFile']);
    Route::post('get-share-file-detail' , [FileController::class , 'getShareFile']);
    Route::get('get-frequently-ask-questions' , [HomeController::class , 'getFrequentlyAskQuestion']);
    Route::get('suggested-skills' , [HomeController::class , 'suggestedSkills']);
    Route::post('change-password' , [UserController::class , 'changePassword']);
    Route::get('delete-profile' , [UserController::class , 'deleteUser']);
    Route::post('change-push-notification' , [UserController::class , 'setPushNotification']);
    Route::post('update-user-profile' , [UserController::class , 'updateUserProfile']);
    Route::get('country-list' , [HomeController::class , 'getCountries']);
    Route::post('city-list' , [HomeController::class , 'getCities']);
    Route::post('filter-job' , [JobController::class , 'filterJob']);
    Route::post('profile-detail' , [UserController::class , 'getProfileDetail']);
    Route::post('review-list' , [ReviewController::class , 'getReviewList']);
    Route::post('share-folder-files' , [FolderController::class , 'getShareFolderFile']);
    // personal job chat
    Route::get('chat/{personalJobId}', [PersonalJobChatController::class, 'index']);
    Route::post('chat/send', [PersonalJobChatController::class, 'store']);
    Route::post('/broadcasting/auth', function (Request $request) {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $socketId = $request->input('socket_id');
        $channelName = $request->input('channel_name');

        // Build your custom channel data
        $channelData = [
            'user_id' => $user->id,
            'user_info' => [
                'name' => $user->full_name,
                'email' => $user->email,
            ],
        ];

        $encodedChannelData = json_encode($channelData);

        // Build the signature manually
        $stringToSign = "{$socketId}:{$channelName}:{$encodedChannelData}";
        $authSignature = hash_hmac('sha256', $stringToSign, env('PUSHER_APP_SECRET'));

        $auth = env('PUSHER_APP_KEY') . ':' . $authSignature;

        // Return in Flutter's expected format
        return response()->json([
            'auth' => $auth,
            'shared_secret' => hash('sha256', $user->id . now()), // optional, your logic
            'channel_data' => $encodedChannelData,
        ]);
    });

});

/* Editor Routes*/
Route::middleware(['verify.authentication' , 'api.editor.verify'])->group(function(){
    Route::post('editor/create-account', [StripeEditorController::class, 'createAccount']);
    Route::post('update-profile' , [UserController::class , 'updateProfile']);
    Route::post('update-editor-profile' , [UserController::class , 'updateEditorProfile']);
    Route::post('add-editor-portfolio' , [UserController::class , 'addEditorPortfolio']);
    Route::post('upload-portfolio-file' , [UserController::class , 'uploadPortfolioFile']);
    Route::post('update-single-editor-portfolio' , [UserController::class , 'updateSingleEditorPortfolio']);
    Route::post('update-editor-education' , [UserController::class , 'updateEditorEducation']);
    Route::post('update-hourly-rate' , [UserController::class , 'updateHourlyRate']);
    Route::post('update-address' , [UserController::class, 'updateAddress']);
    Route::post('update-biography' , [UserController::class ,'updateBiography']);
    Route::post('update-language' , [UserController::class , 'updateLanguage']);
    Route::post('delete-skill' , [UserController::class , 'deleteSkill']);
    Route::post('add-editor-skill' , [UserController::class ,'addEditorSkill']);
    Route::post('update-education' , [UserController::class ,'updateEducation']);
    Route::post('add-job-request' , [JobController::class , 'addJobRequest']);
    Route::get('proposal-list' , [JobController::class , 'getProposalList']);
    Route::get('job-list' , [JobController::class , 'getJobList']);
    Route::get('unassigned-job-list' , [JobController::class , 'getUnassignedJobs']);
    Route::post('share-files' , [FolderController::class , 'shareFile']);
    Route::post('direct-share-file-upload' , [FolderController::class , 'directShareFileUpload']);
    // Route::post('share-files' , function(){dd("inside share file");});
    Route::post('getshares' , [FolderController::class , 'getshares']);
    Route::post('delete-share-file' , [FileController::class , 'deleteShareFile']);
    Route::get('cancel-jobs' , [JobController::class , 'cancelJobs']);
    Route::get('done-jobs' , [JobController::class , 'doneJobs']);
    Route::post('job-folder' , [FolderController::class , 'getJobFolder']);
    Route::post('get-filtered-job' , [JobController::class , 'filterJob']);
    Route::post('add-social-link' , [UserController::class , 'addSocialLink']);
    Route::post('update-social-link' , [UserController::class , 'updateSocialLink']);
    Route::post('upload-portfolio-video' , [UserController::class , 'uploadPortfolioVideo']);
    Route::post('delete-portfolio-video' , [UserController::class , 'deletePortfolioVideo']);
    Route::get('get-portfolio-video' , [UserController::class , 'portfolioVideo']);
    Route::post('extend-job-delivery-date', [JobController::class, 'extendJobDeliveryDate']);
});


/* Client Routes*/
Route::middleware(['verify.authentication' , 'api.client.verify'])->group(function(){
    Route::post('client/create-account', [StripeClientController::class, 'createAccount']);
    Route::post("create-folder" , [FolderController::class , 'createClientFolder']);
    Route::post("post-job" , [JobController::class , 'addJob']);
    Route::get("client-jobs" , [JobController::class , 'clientJob']);
    Route::get("request-list" , [JobController::class , 'requestList']);
    Route::post("award-job" , [JobController::class , "awardJob"]);
    Route::get("awarded-job-list", [JobController::class ,"awardedJobList"]);
    Route::post("add-favourite" , [UserController::class , 'addFavourite']);
    Route::post("delete-favourite" , [UserController::class , 'deleteFavourite']);
    Route::get('favourite-list' , [UserController::class , 'getFavouriteList']);
    Route::post('add-folder-file', [FolderController::class, 'addFolderFile']);
    Route::get('editor-list' , [UserController::class , 'getEditorList']);
    Route::post('pay-bill' , [BillingController::class , 'payBill']);
    Route::get('folder-list' , [FolderController::class , 'getClientFolders']);
    Route::post('folder-detail' , [FolderController::class , 'getFolderDetail']);
    Route::post('upload-files' , [FileController::class , 'uploadClientFile']);
    Route::post('upload-client-files-directly' , [FileController::class , 'uploadClientFileDirectly']);
    Route::post('delete-file' , [FileController::class , 'deleteClientFile']);
    Route::post('update-folder' , [FolderController::class, 'updateClientFolder']);
    Route::post('delete-folder' , [FolderController::class , 'deleteClientFolder']);
    Route::post('get-folder-files' , [FolderController::class , 'getFolderFiles']);
    Route::post('process-payment', [BillingController::class , 'processBilling']);
    Route::get('public-key' , [BillingController::class , 'getPublicKey']);
    Route::post('get-payment-intent' , [BillingController::class , 'getPaymentIntent']);
    Route::post('complete-job' , [JobController::class , 'doneAwardedJob']);
    Route::post('cancel-job' , [JobController::class , 'cancelAwardedJob']);
    Route::post('add-favourite-request' , [FavouriteRequestController::class , 'addFavouriteRequest']);
    Route::post('get-favourite-request' , [FavouriteRequestController::class , 'getFavouriteRequest']);
    Route::post('delete-favourite-request' , [FavouriteRequestController::class , 'deleteFavouriteRequest']);
    Route::get('posted-jobs' , [JobController::class , 'postJobList']);
    Route::get('awarded-jobs' , [JobController::class , 'awardJobList']);
    Route::post('job-request-list' , [JobController::class , 'jobRequestList']);
    Route::post('award-job-request' , [JobController::class , 'awardedJobRequest']);
    Route::post('unawarded-job-request' , [JobController::class ,'unawardedJobRequest']);
    Route::post('add-job-review' , [ReviewController::class ,'addUpdateReview']);
    Route::get('ongoing-jobs' , [JobController::class , 'ongoingJob']);
    Route::get('completed-jobs' , [JobController::class , 'completedJob']);
    Route::post('get-job-review' , [JobController::class , 'jobReview']);
    Route::post('get-client-folder' , [FolderController::class , 'getClientFolder']);
    Route::get('client-profile-detail' , [UserController::class , 'getClientProfile']);
    Route::post('delete-job' , [JobController::class , 'deleteJob']);
    Route::post('search-folder' , [FolderController::class , 'searchFolder']);
    Route::post('get-editor-review' , [ReviewController::class , 'getEditorReviews']);
    Route::post('extend-job-delivery-date-request', [JobController::class, 'extendJobDeliveryDateRequest']);
    Route::get('get-editor-portfolio-video' , [UserController::class , 'getEditorPortfolioVideo']);
});

// Route::post('add-links' , [UserController::class , 'addLinks']);
// Route::post('update-profile' , [UserController::class , 'updateProfile']);


