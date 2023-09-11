<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repository\FilesHandler;
use Illuminate\Support\Facades\Validator;

class FileController extends Controller
{
    protected $filesHandler;

    public function __construct(FilesHandler $filesHandler)
    {
        $this->filesHandler = $filesHandler;    
    }

    public function uploadClientFile(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'folder_id' => 'required',
                'files' => 'required',
                'files.*' => 'mimes:mp4,webm,png,jpg,jpeg,PNG,JPG,JPEG'

            ]);   

            // dd("here boss");
            
            if ($validator->fails()) {

                return response()->json(["success" => false, "msg" => "Something Went Wrong", "error" => $validator->getMessageBag()] ,400);
            
            } else {
                
                $response = $this->filesHandler->uploadMedia($request);
                
                return response()->json($response);
            }

        }catch(\Exception $e){
            return response()->json(["success" => false, "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        }
    }

    public function deleteClientFile(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'file_id' => 'required',
            ]);   
            
            if ($validator->fails()) {

                return response()->json(["success" => false, "msg" => "Something Went Wrong", "error" => $validator->getMessageBag()] ,400);
            
            } else {
                
                $response = $this->filesHandler->deleteMedia($request);
                
                return response()->json($response);
            }

        }catch(\Exception $e){
            return response()->json(["success" => false, "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        }
    }

    
    public function deleteShareFile(Request $request)
    {
        try{

            $validator = Validator::make($request->all(), [
                'file_id' => 'required',
            ]);   
            
            if ($validator->fails()) {

                return response()->json(["success" => false, "msg" => "Something Went Wrong", "error" => $validator->getMessageBag()] ,400);
            
            } else {
                
                $response = $this->filesHandler->deleteShareMedia($request);
                
                return response()->json($response);
            }

        }catch(\Exception $e){
            return response()->json(["success" => false, "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        }
    }

}
