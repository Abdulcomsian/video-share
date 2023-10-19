<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repository\FavouriteRequestHandler;
use Illuminate\Support\Facades\Validator;

class FavouriteRequestController extends Controller
{
    protected $frHandler;

    function __construct(FavouriteRequestHandler $frHandler){
        $this->frHandler = $frHandler;
    }

    public function addFavouriteRequest(Request $request){
        try{
            $validator = Validator::make($request->all() , [
                "request_id" => "required|numeric",
                "job_id" => "required|numeric"
            ]);

            if($validator->fails())
            {
                return response()->json(["success" => false , "msg" => "Something Went Wrong" , "error"=> $validator->getMessageBag()] ,400);

            }else{
                $response = $this->frHandler->addFavouriteJobRequest($request);
                return response()->json($response);
            }
    
        }catch(\Exception $e){
            return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        }
    }

    public function getFavouriteRequest(Request $request){
        try{
            $validator = Validator::make($request->all() , [
                "job_id" => "required|numeric"
            ]);

            if($validator->fails())
            {
                return response()->json(["success" => false , "msg" => "Something Went Wrong" , "error"=> $validator->getMessageBag()] ,400);

            }else{
                $response = $this->frHandler->getFavouriteJobRequest($request);
                return response()->json($response);
            }
    
        }catch(\Exception $e){
            return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        }
    }

    public function deleteFavouriteRequest(Request $request){
        try{
            $validator = Validator::make($request->all() , [
                "fr_id" => "required|numeric"
            ]);

            if($validator->fails())
            {
                return response()->json(["success" => false , "msg" => "Something Went Wrong" , "error"=> $validator->getMessageBag()] ,400);

            }else{
                $response = $this->frHandler->removeFavouriteJobRequest($request);
                return response()->json($response);
            }
    
        }catch(\Exception $e){
            return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        }
    }
}
