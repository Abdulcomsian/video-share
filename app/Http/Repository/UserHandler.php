<?php 

namespace App\Http\Repository;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\VerificationMail;

class UserHandler{
    
    public function findUser($email , $password , $type)
    {
        $token = auth()->attempt(["email" => $email , "password" => $password]);
        
        if(!$token)
        {
            return response()->json(["success" => false , "msg" => "Something went wrong" , "error" => "unauthorized"]);
        
        }else{

            $jwt = $this->respondWithToken($token);
            
            if($type == "register"){
                $verificationCode = rand(111111,999999);
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
                'expires_in'=> JWTAuth::factory()->getTTL() * 60,
                'user'=> auth()->user()
        ];
    }

}