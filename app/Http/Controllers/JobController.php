<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ PersonalJob };
use App\Http\Repository\{JobHandler};
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    protected $jobHandler;

    public function __construct(JobHandler $jobHandler)
    {
        $this->jobHandler = $jobHandler;
    }
    
    public function addJob(Request $request)
    {
        try{
            $validator = Validator::make($request->all() , [
                "title" => "required|string",
                "description" =>  "required|string",
                "budget" => "required|numeric",
                "deadline" => "required|date",
                "skills" => "required|string",
                "folder_id" => "required|numeric"
            ]);

            if($validator->fails())
            {
                return response()->json(["success" => false , "msg" => "Something Went Wrong" , "error"=> $validator->getMessageBag()]);

            }else{
                $response = $this->jobHandler->addClientJob($request);
                return response()->json($response);
            }
    
        }catch(\Exception $e){
            return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()]);
        }

    }


    public function clientJob()
    {
        try{
            $response = $this->jobHandler->clientJobList();
            return response()->json($response);
    
        }catch(\Exception $e){
            return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()]);
        }
    }


    public function requestList()
    {
        try{
            $response = $this->jobHandler->jobRequestList();
            return response()->json($response);
    
        }catch(\Exception $e){
            return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()]);
        }
    }

    public function addJobRequest(Request $request)
    {
        try{
            $validator = Validator::make( $request->all(), [
                            "job_id" => "required|numeric",
                            "description" => "required|string",
                            "bid_price" => "required|numeric"
                        ]);
            if($validator->fails())
            {
                return response()->json(["success" => false , "msg" => "Something Went Wrong" ,"error" => $validator->getMessageBag()]);
            }else{
                $response = $this->jobHandler->addJobProposal($request);
                return response()->json($response);
            }
    
        }catch(\Exception $e){
            return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()]);
        }
    }

   public function awardedJobList()
   {
    try{
        $response = $this->jobHandler->awardedJobList();
        return response()->json($response);

    }catch(\Exception $e){
        return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()]);
    }
   }
}
