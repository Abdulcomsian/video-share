<?php

namespace App\Http\Controllers;

use App\Http\AppConst;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Http\Repository\UserHandler;
use App\Services\FirebaseAuthService;
use App\Models\User;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $userHandler;
    protected $firebaseAuth;

    public function __construct(UserHandler $userHandler, FirebaseAuthService $firebaseAuth)
    {
        $this->userHandler  = $userHandler;
        $this->firebaseAuth = $firebaseAuth;
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

                $user = User::create([
                    "full_name" => $fullName,
                    "email" => $email,
                    "password" => Hash::make($password),
                    "type" => $type
                ]);

                if($type == AppConst::EDITOR){
                    User::where('id' , auth()->user()->id)->update(['verification_code' => rand(1111,9999)]);
                }
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

    public function refreshToken()
    {
        try {
            $newToken = auth()->guard('api')->refresh();
            $jwt = $this->userHandler->respondWithToken($newToken);

            return response()->json([
                "success" => true,
                "msg"     => "Token refreshed successfully",
                "token"   => $jwt
            ]);

        } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
            return response()->json([
                "success" => false,
                "msg"     => "This token has already been refreshed, please use the new token",
                "error"   => "token_blacklisted"
            ], 401);

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json([
                "success" => false,
                "msg"     => "Session has expired, please login again",
                "error"   => "refresh_token_expired"
            ], 401);

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "msg"     => "Something went wrong",
                "error"   => $e->getMessage()
            ], 401);
        }
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(["success" => "successfully logout"]);

    }

    public function socialLogin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "firebase_token" => "required|string",
                "type"           => "nullable|numeric|in:1,2",
            ]);

            if ($validator->fails()) {
                return response()->json([
                    "success" => false,
                    "msg"     => "Something went wrong",
                    "error"   => $validator->getMessageBag()
                ], 400);
            }

            // Verify Firebase ID token server-side (cryptographic verification)
            $socialData = $this->firebaseAuth->verifyIdToken($request->firebase_token);

            // Validate the provider is one we support
            $allowedProviders = ['google', 'apple', 'microsoft'];
            if (!in_array($socialData['provider'], $allowedProviders)) {
                return response()->json([
                    "success" => false,
                    "msg"     => "Something went wrong",
                    "error"   => "Unsupported login provider"
                ], 400);
            }

            return $this->userHandler->findOrCreateSocialUser(
                $socialData,
                $request->type ? (int) $request->type : null
            );

        } catch (FailedToVerifyToken $e) {
            return response()->json([
                "success" => false,
                "msg"     => "Something went wrong",
                "error"   => "Invalid or expired token"
            ], 401);

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "msg"     => "Something went wrong",
                "error"   => $e->getMessage()
            ], 401);
        }
    }


}
