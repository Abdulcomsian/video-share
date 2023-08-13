<?php 

namespace App\Http\Repository;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use App\Models\{ User , Favourite };
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


            return response()->json(["success" => true , "msg" => $msg , 'token' => $jwt]);
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

        $profile = User::with('editorProfile' ,'skills')->where('id' , $userId)->first();

        return ['success' => true , 'profile' => $profile];
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

}