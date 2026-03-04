<?php

namespace App\Http\Repository;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use App\Models\{ User , Favourite, Files, Review , PersonalJob, Address , EditorPortfolio, PortfolioVideo, SocialLink};
use App\Mail\ {VerificationMail , TokenMail};
use App\Http\AppConst;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Repository\AwsHandler;
use app\Http\Repository\StripeService;

class UserHandler{

    protected $awsHandler, $stripeService;

    function __construct(AwsHandler $awsHandler, StripeService $stripeService)
    {
        $this->awsHandler = $awsHandler;
        $this->stripeService = $stripeService;
    }

    public function findUser($email , $password , $type)
    {
        $token = auth()->guard('api')->attempt(["email" => $email , "password" => $password]);

        if(!$token)
        {
            return response()->json(["success" => false , "msg" => "Something went wrong" , "error" => "Invalid email or password"] , 400);

        }else{

            $jwt = $this->respondWithToken($token);

            if($type == "register"){
                $verificationCode = rand(1111,9999);
                User::where('id' , auth()->user()->id)->update(['verification_code' => $verificationCode]);
                Mail::to($email)->send(new VerificationMail($verificationCode ));
                $msg = "User Added Successfully";

            }else{
                $msg = "User Signed In Successfully";
            }


            $baseUrl = public_path("uploads");

            if(auth()->user()->type == AppConst::EDITOR){

                    $user = User::where('id' , auth()->user()->id)->first();
                    User::with('editorProfile' ,'skills' , 'education' , 'portfolio' )->where('id' , auth()->user()->id)->first();
                    $stripeId = $user->stripe_account_id;

                    if($stripeId == '')
                    {
                        $updateOnboardingStatusArr = [
                            'onboarding' => 0
                        ];
                    }
                    else
                    {

                        $account = $this->stripeService->getEditorStripeDetailsById($stripeId);

                        if (empty($account->requirements->disabled_reason) &&
                            empty($account->requirements->currently_due) &&
                            empty($account->requirements->past_due))
                        {

                            $updateOnboardingStatusArr = [
                                'onboarding' => 1
                            ];

                        }
                        else
                        {

                            $updateOnboardingStatusArr = [
                                'onboarding' => 0
                            ];

                        }

                    }

                    $this->updateUserByConditions($updateOnboardingStatusArr, ['id' => $user->id]);

                $profile = User::with('editorProfile' ,'skills' , 'education' , 'portfolio' )->where('id' , auth()->user()->id)->first();

                $editorProfileAndSkill = ( isset($profile->editorProfile) && !is_null($profile->editorProfile)) && count($profile->skills) ? true :  false;

                $editorPortfolio = count($profile->portfolio) ? true : false;

                $editorEducation = count($profile->education) ? true : false;

                $editorPerHourRate = ( isset($profile->editorProfile) && !is_null($profile->editorProfile) ) && (isset($profile->editorProfile->amount_per_hour) && !is_null($profile->editorProfile->amount_per_hour)) ? true : false;

                return response()->json(["success" => true , "msg" => $msg , 'token' => $jwt , 'baseUrl' => $baseUrl , "editorProfileAndSkill" => $editorProfileAndSkill , "editorPortfolio" => $editorPortfolio , "editorEducation" => $editorEducation, "editorPerHourRate" => $editorPerHourRate]);

            }else{
                return response()->json(["success" => true , "msg" => $msg , 'token' => $jwt , 'baseUrl' => $baseUrl]);
            }


        }
    }

    public function respondWithToken($token)
    {
        return [
                'access_token'=>$token,
                'token_type'=>'bearer',
                'expires_in'=> JWTAuth::factory()->getTTL() * 60,
                'user'=> auth()->user(),
                'can_change_password'=> !empty(auth()->user()->password)

        ];
    }


    public function findOrCreateSocialUser(array $socialData, ?int $type)
    {
        $firebaseUid = $socialData['uid'];
        $email       = $socialData['email'];
        $name        = $socialData['name'];
        $provider    = $socialData['provider'];

        if (empty($email)) {
            return response()->json([
                "success" => false,
                "msg"     => "Something went wrong",
                "error"   => "Email not provided by social provider"
            ], 400);
        }

        // Try to find by firebase_uid first (most reliable)
        $user = User::where('firebase_uid', $firebaseUid)->first();

        if (!$user) {
            // Try to find by email (handles account linking)
            $user = User::where('email', $email)->first();

            if ($user) {
                // Check type mismatch before linking
                $typeNames = [AppConst::CLIENT => 'Client', AppConst::EDITOR => 'Editor'];
                if (!is_null($type) && $user->type != $type) {
                    return response()->json([
                        "success" => false,
                        "msg"     => "This account is already registered as " . ($typeNames[$user->type] ?? 'unknown'),
                        "error"   => "Account already exists with a different user type"
                    ], 409);
                }

                // Account linking: existing email/password user now using social login
                $user->firebase_uid   = $firebaseUid;
                $user->login_provider = $provider;

                if (is_null($user->email_verified_at)) {
                    $user->email_verified_at = now();
                }

                $user->save();
            } else {
                // Brand new user — type is required for registration
                if (is_null($type)) {
                    return response()->json([
                        "success" => false,
                        "msg"     => "Something went wrong",
                        "error"   => "Type is required for new user registration"
                    ], 400);
                }

                $user = User::create([
                    'full_name'         => $name ?? explode('@', $email)[0],
                    'email'             => $email,
                    'password'          => null,
                    'type'              => $type,
                    'firebase_uid'      => $firebaseUid,
                    'login_provider'    => $provider,
                    'email_verified_at' => now(),
                ]);
            }
        } else {
            // User found by firebase_uid — check type mismatch
            $typeNames = [AppConst::CLIENT => 'Client', AppConst::EDITOR => 'Editor'];
            if (!is_null($type) && $user->type != $type) {
                return response()->json([
                    "success" => false,
                    "msg"     => "This account is already registered as " . ($typeNames[$user->type] ?? 'unknown'),
                    "error"   => "Account already exists with a different user type"
                ], 409);
            }

            // Update provider if changed
            if ($user->login_provider !== $provider) {
                $user->login_provider = $provider;
                $user->save();
            }
        }

        // Generate JWT directly from user model (no password needed)
        $token = auth()->guard('api')->login($user);

        if (!$token) {
            return response()->json([
                "success" => false,
                "msg"     => "Something went wrong",
                "error"   => "Could not generate token"
            ], 500);
        }

        $jwt = $this->respondWithToken($token);
        $msg = "User Signed In Successfully";
        $baseUrl = public_path("uploads");

        if ($user->type == AppConst::EDITOR) {
            $profile = User::with('editorProfile', 'skills', 'education', 'portfolio')
                           ->where('id', $user->id)->first();
            $stripeId = $user->stripe_account_id;

            if ($stripeId == '') {
                $updateOnboardingStatusArr = ['onboarding' => 0];
            } else {
                $account = $this->stripeService->getEditorStripeDetailsById($stripeId);
                if (empty($account->requirements->disabled_reason) &&
                    empty($account->requirements->currently_due) &&
                    empty($account->requirements->past_due)) {
                    $updateOnboardingStatusArr = ['onboarding' => 1];
                } else {
                    $updateOnboardingStatusArr = ['onboarding' => 0];
                }
            }

            $this->updateUserByConditions($updateOnboardingStatusArr, ['id' => $user->id]);

            $editorProfileAndSkill = (isset($profile->editorProfile) && !is_null($profile->editorProfile)) && count($profile->skills) ? true : false;
            $editorPortfolio = count($profile->portfolio) ? true : false;
            $editorEducation = count($profile->education) ? true : false;
            $editorPerHourRate = (isset($profile->editorProfile) && !is_null($profile->editorProfile)) && (isset($profile->editorProfile->amount_per_hour) && !is_null($profile->editorProfile->amount_per_hour)) ? true : false;

            return response()->json([
                "success" => true, "msg" => $msg, 'token' => $jwt, 'baseUrl' => $baseUrl,
                "editorProfileAndSkill" => $editorProfileAndSkill, "editorPortfolio" => $editorPortfolio,
                "editorEducation" => $editorEducation, "editorPerHourRate" => $editorPerHourRate
            ]);
        }

        return response()->json(["success" => true, "msg" => $msg, 'token' => $jwt, 'baseUrl' => $baseUrl]);
    }

    public function addEditorFavourite($request)
    {
        $editorId = $request->editor_id;
        $clientId = auth()->user()->id;

        Favourite::create([
          "editor_id" => $editorId,
          "client_id" => $clientId
        ]);

        return ["success"=> true , "msg" => "Editor Add To Favourite Successfully"];

    }

    public function deleteEditorFavourite($request){

        $editorId = $request->editor_id;

        Favourite::where('editor_id' , $editorId)->where('client_id' , auth()->user()->id)->delete();

        return ["success"=> true , "msg" => "Editor Remove From Favourite Successfully"];
    }

    public function favouriteList()
    {
        $userId = auth()->user()->id;

        $favouriteList = User::with('favourite.editor.skills', 'favourite.editor.editorProfile')->where('id' , $userId)->first();

        return ["success" => true , 'favourites' => $favouriteList];
    }

    public function profileDetail($editorId)
    {
        $userId = $editorId;

        $reviews = Review::whereHas('job' , function($query) use ($userId){
                            $query->whereHas('doneRequest' , function($query1) use ($userId){
                                 $query1->where('editor_id' , $userId);
                            });
                        })
                        ->with('job.user')
                        ->orderBy('id' , 'desc')
                        ->get();


        $totalReview = $reviews->count();

        $lastReviewComment = $totalReview > 0 ? $reviews[0] : null;

        $averageReviewRating =  $totalReview > 0 ? $reviews->pluck('rating')->sum()/$totalReview : 0;

        $profile = User::with('address.country', 'address.city' , 'editorProfile' ,'skills' , 'education' , 'portfolio' , 'portfolioVideo' ,'socialLink' )->where('id' , $userId)->first();

        $editorProfileAndSkill = ( isset($profile->editorProfile) && !is_null($profile->editorProfile)) && count($profile->skills) ? true :  false;

        $editorPortfolio = count($profile->portfolio) ? true : false;

        $editorEducation = count($profile->education) ? true : false;

        $editorPerHourRate = ( isset($profile->editorProfile) && !is_null($profile->editorProfile) ) && (isset($profile->editorProfile->amount_per_hour) && !is_null($profile->editorProfile->amount_per_hour)) ? true : false;

        $userDetails = User::with('doneJob' , 'cancelJob')->where('id' , $userId)->first();

        $doneJobCount = $userDetails->doneJob->count();

        $cancelJobCount = $userDetails->cancelJob->count();

        $editorStatus = false;

        if(auth()->user()->type == AppConst::CLIENT){
            $favouriteCount = Favourite::where('client_id' , auth()->user()->id)
                               ->where('editor_id' , $editorId)
                               ->count();

            $editorStatus = $favouriteCount ? true : false;

        }


        return [
                 'success' => true ,
                 'profile' => $profile ,
                 'editorProfileAndSkill' => $editorProfileAndSkill ,
                 'editorPortfolio' => $editorPortfolio,
                 'editorEducation' => $editorEducation,
                 'editorPerHourRate' => $editorPerHourRate,
                 'doneJobsCount' => $doneJobCount,
                 'cancelJobCount' => $cancelJobCount,
                 'timelyDeliveredJobCount' => $doneJobCount,
                 'totalReview' => $totalReview,
                 'averageReviewRating' => $averageReviewRating,
                 'lastReview' => $lastReviewComment,
                 'editorStatus' => $editorStatus
               ];
    }

    public function editorList()
    {
        $editorList  = User::where('type', AppConst::EDITOR)->orderBy('id' ,'desc')->get();
        return ['success' => true , 'editorList' => $editorList];
    }

    public function clientList()
    {
        $clientList = User::where('type' , AppConst::CLIENT)->orderBy('id' , 'desc')->get();
        return ['success' => true , 'clientList' => $clientList];
    }

    public function updateProfileImage($request)
    {
        $file = $request->file('file');
        $fileName = str_replace(' ' , '', $file->getClientOriginalName());
        $newName = time()."-".$fileName;
        $file->move(public_path("uploads") , $newName );
        User::where('id' , auth()->user()->id)->update(['profile_image' => $newName]);
        return ['success' => true , 'msg' => 'Profile Image Updated Successfully'];
    }

    public function changePassword($request){
        $oldPassword = $request->old_password;
        $newPassword = $request->new_password;

        if(!Hash::check($oldPassword, auth()->user()->password)){
            return ['status' => false , 'msg' => 'Please Add Correct Password'];
        }

        $user = User::where('id' , auth()->user()->id)->first();
        $user->password = Hash::make($newPassword);
        $user->save();

        return ['status' => true , 'msg' => 'Password Changed Successfully'];

    }

    public function forgetPassword($request){
        $email = $request->email;
        $user = User::where('email' , $email)->first();

        if(!$user){
            return ['status' => false , 'msg' => 'No User Found With This Email'];
        }

        $verificationToken = rand(1111 , 9999);

        $user->token = $verificationToken;
        $user->save();

        Mail::to($email)->send(new TokenMail($verificationToken));

        return ['status' => true , 'msg' => 'A verification code has been sent to your mail please check'];

    }

    public function updatePassword($request){
        $email = $request->email;
        $verificationCode = $request->verification_code;
        $newPassword = $request->new_password;

        $user = User::where(['email' => $email])->first();

        if(!$user){
            return ['status' => false , 'msg' => 'No User Found With This Email'];
        }

        if($verificationCode != $user->token){
            return ['status' => false , 'msg' => "Verification Code Doesn't match"];
        }


        $user->password = Hash::make($newPassword);
        $user->save();

        return ['status' => true , 'msg' => 'Password has been updated'];

    }

    public function sendVerificationCode($request){
        $verificationCode = rand(1111,9999);
        $user = User::where('email' , $request->email)->first();

        if(!$user){
            return ['status'=>false ,'msg' => 'No user found with this email'];
        }

        $user->verification_code = $verificationCode;
        $user->save();
        Mail::to($request->email)->send(new VerificationMail($verificationCode ));

        return ['status' => true ,'msg' => 'Verification code has been sent to your email'];
    }

    public function updatePushNotification($request){

        User::where('id' , auth()->user()->id)->update(['notification_status' => $request->status]);

        return ['status' => true , 'msg'=> 'Push Notification Updated Successfully'];
    }

    public function deleteUserProfile(){

        User::where('id' , auth()->user()->id)->delete();

        return ['status' => true , 'msg'=> 'User Deleted Successfully'];

    }

    public function clientProfile(){

        $userId = auth()->user()->id;
        $totalJobs  = PersonalJob::where('client_id' , $userId)->count();
        $doneJobs   = PersonalJob::where('client_id' , $userId)->whereIn('status' , ['completed' , 'Completed'])->count();
        $cancelJobs  = PersonalJob::where('client_id' , $userId)->whereIn('status' , ['canceled', 'Canceled'])->count();

        $files = Files::with('folder')->whereHas('folder' , function($query){
                            $query->where('client_id' , auth()->user()->id);
                        })->orderBy('id' , 'desc')->get();

        $user = User::with('address')->where('id' , auth()->user()->id)->first();

        $bucketName = config('filesystems.disks.s3.bucket');

        $bucketAddress = "https://$bucketName.s3.amazonaws.com/";

        return ['totalJobs' => $totalJobs , 'doneJobsCount' => $doneJobs , 'cancelJobsCount' => $cancelJobs , 'recent_files' => $files , 'user' => $user, 'bucketAddress' => $bucketAddress];

    }

    public function updateUserProfile($request){
        $username = $request->full_name;

        User::where("id" , auth()->user()->id)->update(["full_name" => $username]);

        // Address::updateOrCreate(
        //     ["user_id" => auth()->user()->id],
        //     ["user_id" => auth()->user()->id , "country_id" => $request->country_id , "city_id" => $request->city_id , "language" => $request->language , "address" => $request->address],
        // );

        Address::updateOrCreate(
            ["user_id" => auth()->user()->id],
            ["user_id" => auth()->user()->id , "language" => $request->language , "address" => $request->address],
        );

        return ["status" => true , "msg"=>"User Profile Updated Successfully"];

    }


    public function addSocialLink($url , $platform )
    {
        // $socailLinks = [];

        foreach($url as $index => $link){

            $socialLink = SocialLink::where('user_id' , auth()->user()->id)->where('platform' , $platform[$index])->first();

            if($socialLink){
                $socialLink->url = $link;
                $socialLink->save();
                continue;
            }
            else{
                SocialLink::create([
                    'platform' => $platform[$index] , 'url' => $link , 'user_id' => auth()->user()->id
                ]);
            }
        }

        // SocialLink::insert($socailLinks);
        return ["status" => true , "msg"=>"Social links added successfully"];
    }

    public function updateSocialLink($url , $platform)
    {
        $socailLinks = [];

        SocialLink::where('user_id' , auth()->user()->id)->delete();

        foreach($url as $index => $link){
           $socailLinks[] = ['platform' => $platform[$index] , 'url' => $link, 'user_id' => auth()->user()->id];
        }

        SocialLink::insert($socailLinks);
        return ["status" => true , "msg"=>"Social links updated successfully"];
    }

    public function uploadPortfolioVideo($request)
    {
        foreach($request->file('files') as $file)
        {
            $fileName = $file->getClientOriginalName();
            $name = time() . "-" . str_replace(" ", "-", $fileName);
            $check = $this->awsHandler->uploadMedia( "user-porfolio" , $name , $file);
            if($check['success'])
            {
                PortfolioVideo::create(['user_id' => auth()->user()->id , 'video_url' => $name] );
            }
        }

        return ['status' => true , 'msg' => 'Portfolio video updated successfully'];

    }

    public function getPortfolioVideo()
    {
        $bucketName = config('filesystems.disks.s3.bucket');

        $bucketAddress = "https://$bucketName.s3.amazonaws.com/user-porfolio";

        $portfolioVideo = PortfolioVideo::where('user_id' , auth()->user()->id)->get();

        $thumbnailPrefix = asset('uploads');

        return response()->json(['status' => true , 'bucketAddress' => $bucketAddress , 'portfolioVideo' => $portfolioVideo , 'thumbnailPrefix' => $thumbnailPrefix]);
    }


    public function deletePortfolioVideo($request)
    {
        $video = PortfolioVideo::where('id' , $request->id)->first();

        if($video){
            $filename = 'user-porfolio/'.$video->video_url;
            $check = $this->awsHandler->deleteMedia($filename);
            if($check['success']){
                PortfolioVideo::where('id' , $request->id)->delete();
                return ['status' => true , 'msg' => 'Video Deleted Successfully'];
            }else{
                return ['status' => false , 'msg' => 'Something Went Wrong While Deleting Video From Bucket' ];
            }
        }else{
            return ['status' => false , 'msg' => 'video not found' ];
        }



    }

    public function uploadPortfolioFile($request)
    {
        $thumbnailname = null;

        if($request->hasFile('thumbnail'))
        {
            $file = $request->file('thumbnail');
            $thumbnailname = time().'-'.str_replace(" ","_", $file->getClientOriginalName());
            $file->move(public_path("uploads") , $thumbnailname);
        }
        PortfolioVideo::create([
            'thumbnail' => $thumbnailname,
            'video_url' => $request->filename,
            'user_id' => auth()->user()->id
        ]);
        return ['status' => true , 'msg' => 'Portfolio file added successfully'];
    }

    public function getEditorPortfolioVideo($editorId)
    {
        $bucketName = config('filesystems.disks.s3.bucket');

        $bucketAddress = "https://$bucketName.s3.amazonaws.com/user-porfolio";

        $portfolioVideo = PortfolioVideo::where('user_id' , $editorId)->get();

        $thumbnailPrefix = asset('uploads');

        return response()->json(['status' => true , 'bucketAddress' => $bucketAddress , 'portfolioVideo' => $portfolioVideo , 'thumbnailPrefix' => $thumbnailPrefix]);
    }

    public function updateUserByConditions($data, $conditions)
    {
        return User::where($conditions)->update($data);
    }

    public function getRowByColumns($conditions)
    {
        return User::where($conditions)->first();
    }

}
