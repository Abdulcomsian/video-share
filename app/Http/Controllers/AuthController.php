<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Repository\UserHandler;
use App\Models\User;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $userHandler;

    public function __construct(UserHandler $userHandler)
    {
        $this->userHandler = $userHandler;
        // $this->middleware('auth:api', ['except' => ['login' , 'register' ,'verifyUser']]);
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "full_name" => "required|string",
                "email" => "required|string|unique:users,email",
                "password" => "required|string",
                "type" => "required|numeric"
            ]);

            if ($validator->fails()) {
                
                return response()->json(["success" => false, "msg" => "Something went wrong", "error" => $validator->getMessageBag()] , 400);
            
            } else {

                $fullName = $request->full_name;
                $email = $request->email;
                $password = $request->password;
                $type = $request->type;
                
                User::create([
                    "full_name" => $fullName,
                    "email" => $email,
                    "password" => Hash::make($password),
                    "type" => $type
                ]);
                return $this->userHandler->findUser($email, $password , "register");
            }
        } catch (\Exception $e) {

            return response()->json(["success" => false, "msg" => "Something went wrong", "error" => $e->getMessage()] , 401);
        
        }
        

    }

    public function login(Request $request)
    {
        try{
            $validator = Validator::make($request->all() , [
                "email" => "required|string",
                "password"   => "required|string"
            ]);

            if($validator->fails())
            {
                return response()->json(["success" => false, "msg" => "Something went wrong", "error" => $validator->getMessageBag()] , 400); 
            
            }else{
                
                $email = $request->email;
                $password   = $request->password;

                return $this->userHandler->findUser($email, $password , "login");
            
            }

        }catch(\Exception $e){
            
            return response()->json(["success" => false, "msg" => "Something went wrong", "error" => $e->getMessage()] , 401);
        
        }
    }

    public function verifyUser(Request $request)
    {
        try{
            $verificationCode = $request->verification_code;
            $email = $request->email;
            
            $user = User::where('email' , $email )->first();


            if($user->verification_code === $verificationCode)
            {
                $user->email_verified_at = date("Y-m-d H:i:s");
                $user->save();
                return response()->json(['success' => true , 'msg' => 'User verified successfully']);

            }else{
                return response()->json(['success' => false , 'msg' => 'Something Went Wrong' , 'error' => "Verification Code Doesn't Match"] , 400);
            }

        }catch(\Exception $e)
        {
            return response()->json(['success' => false , 'msg' => 'Something Went Wrong' , 'error' => $e->getMessage()] , 401);
        }


    }

    public function logout()
    {
        auth()->logout();
        return response()->json(["success" => "successfully logout"]);
        
    }


}
