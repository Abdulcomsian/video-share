<?php 

namespace App\Http\Repository;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use App\Models\{ User , Favourite, Review};
use App\Mail\VerificationMail;
use App\Http\AppConst;

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

    public function favouriteList()
    {
        $userId = auth()->user()->id;

        $favouriteList = User::with('favourite.editor')->where('id' , $userId)->first();

        return ["success" => true , 'favourites' => $favouriteList];
    }

    public function profileDetail()
    {
        $userId = auth()->user()->id;

        $reviews = Review::whereHas('job' , function($query) use ($userId){
                        $query->whereHas('awardedRequest' , function($query1) use ($userId){
                            $query1->where('editor_id' , $userId);
                        });
                    })->get();

        $totalReview = $reviews->count();


        $averageReviewRating =  $totalReview > 0 ? $reviews->pluck('rating')->sum()/$totalReview : 0;

        $profile = User::with('address','editorProfile' ,'skills' , 'education' , 'portfolio' )->where('id' , $userId)->first();
        
        $editorProfileAndSkill = ( isset($profile->editorProfile) && !is_null($profile->editorProfile)) && count($profile->skills) ? true :  false;

        $editorPortfolio = count($profile->portfolio) ? true : false;

        $editorEducation = count($profile->education) ? true : false;

        $editorPerHourRate = ( isset($profile->editorProfile) && !is_null($profile->editorProfile) ) && (isset($profile->editorProfile->amount_per_hour) && !is_null($profile->editorProfile->amount_per_hour)) ? true : false; 

        $doneJobCount = User::with('doneJob')->where('id' , $userId)->count();

        $cancelJobCount = User::with('cancelJob')->where('id' , $userId)->count();

        
        return [ 
                 'success' => true , 
                 'profile' => $profile , 
                 'editorProfileAndSkill' => $editorProfileAndSkill , 
                 'editorPortfolio' => $editorPortfolio, 
                 'editorEducation' => $editorEducation, 
                 'editorPerHourRate' => $editorPerHourRate,
                 'doneJobsCount' => $doneJobCount,
                 'cancelJobCount' => $cancelJobCount,
                 'timelyDiliveredJobCount' => $doneJobCount,
                 'totalReview' => $totalReview,
                 'averageReviewRating' => $averageReviewRating
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

}