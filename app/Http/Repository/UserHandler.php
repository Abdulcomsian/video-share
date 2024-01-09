<?php 

namespace App\Http\Repository;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use App\Models\{ User , Favourite, Files, Review , PersonalJob, Address};
use App\Mail\ {VerificationMail , TokenMail};
use App\Http\AppConst;
use Illuminate\Support\Facades\Hash;

class UserHandler{
    
    public function findUser($email , $password , $type)
    {
        $token = auth()->guard('api')->attempt(["email" => $email , "password" => $password]);
        
        if(!$token)
        {
            return response()->json(["success" => false , "msg" => "Something went wrong" , "error" => "unauthorized"] , 400);
        
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
                'expires_in'=> JWTAuth::factory()->getTTL() * 180,
                'user'=> auth()->user()
        ];
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

        $profile = User::with('address.country', 'address.city' , 'editorProfile' ,'skills' , 'education' , 'portfolio' )->where('id' , $userId)->first();
        
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

        Address::updateOrCreate(
            ["user_id" => auth()->user()->id],
            ["user_id" => auth()->user()->id , "country_id" => $request->country_id , "city_id" => $request->city_id , "language" => $request->language , "address" => $request->address],
        );

        return ["status" => true , "msg"=>"User Profile Updated Successfully"];

    }

}