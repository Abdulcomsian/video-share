<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Repository\{ EditorHandler , UserHandler };
use App\Models\User;

class UserController extends Controller
{
    protected $editorHandler;
    protected $userHandler;

    public function __construct(EditorHandler $editorHandler , UserHandler $userHandler)
    {
        $this->editorHandler = $editorHandler;    
        $this->userHandler  = $userHandler;
    }

    public function updateEditorProfile(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "title" => "required|string",
                "bio" => "required|string",
                "service_offering" => "required",
            ]);


            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
                
            }else{
                $response = $this->editorHandler->editorProfile($request);

                return response()->json($response);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }


    public function updateEditorPortfolio(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "link" => "required|string",
            ]);

            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
            
            }else{

                $response = $this->editorHandler->editorPortfolio($request);

                return response()->json($response);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }


    public function updateEditorEducation(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "education" => "required|string",
            ]);

            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
            
            }else{
                $response = $this->editorHandler->editorEducation($request);

                return response()->json($response);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }

    public function updateHourlyRate(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "amount_per_hour" => "required|numeric",
            ]);

            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
            
            }else{

                $response = $this->editorHandler->editorHourlyRate($request);

                return response()->json($response);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }


    public function updateAddress(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "country" => "required|string",
                "address" => "required|string",
                "city"    => "required|string",
                "language"=> "required|string"
            ]);

            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
            
            }else{

                $response = $this->editorHandler->updateEditorAddress($request);

                return response()->json($response);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }

    public function updateBiography(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "biography" => "required|string",
            ]);

            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
            
            }else{

                $response = $this->editorHandler->updateEditorBiography($request);

                return response()->json($response);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }

    public function updateLanguage(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "language" => "required|string",
            ]);

            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
            
            }else{

                $response = $this->editorHandler->updateEditorLanguage($request);

                return response()->json($response);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }

    public function deleteSkill(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "skill_id" => "required|numeric",
            ]);

            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
            
            }else{

                $response = $this->editorHandler->deleteEditorSkill($request);

                return response()->json($response);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }

    public function addEditorSkill(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "skills" => "required|string",
            ]);

            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
            
            }else{

                $response = $this->editorHandler->addMoreEditorSkill($request);

                return response()->json($response);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }

    public function updateEducation(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "institute"   => "required|string",
                "degree"        => "required|string",
                "start_date"    => "required|string",
                "end_date"      => "required|string"
            ]);

            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
            
            }else{

                $response = $this->editorHandler->updateEditorEducation($request);

                return response()->json($response);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }

    public function addFavourite(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                "editor_id"   => "required|numeric",
            ]);

            if($validator->fails())
            {
                return response()->json(["success"=>false , "msg" => "Something went wrong" , "error" => $validator->getMessageBag()] ,400);
            
            }else{

                $response = $this->userHandler->addEditorFavourite($request);

                return response()->json($response);
            }

        
        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        } 
    }


    public function getFavouriteList()
    {
        try{

            $response = $this->userHandler->favouriteList();

            return response()->json($response);

        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        } 
    }

    public function getProfileDetail()
    {
        try{

            $response = $this->userHandler->profileDetail();

            return response()->json($response);

        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        } 
    }

    public function getEditorList()
    {
        try{

            $response = $this->userHandler->editorList();

            return response()->json($response);

        }catch(\Exception $e)
        {
            return response()->json(['success' =>false , 'msg' => "Something Went Wrong" , "error" => $e->getMessage()] ,400);
        }
    }

}
