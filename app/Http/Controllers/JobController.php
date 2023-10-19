<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repository\{JobHandler , StripeService};
use Illuminate\Support\Facades\Validator;

class JobController extends Controller
{
    protected $jobHandler;
    protected $stripeService;

    public function __construct(JobHandler $jobHandler , StripeService $stripeService)
    {
        $this->jobHandler = $jobHandler;
        $this->stripeService = $stripeService;
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
                return response()->json(["success" => false , "msg" => "Something Went Wrong" , "error"=> $validator->getMessageBag()] ,400);

            }else{
                $response = $this->jobHandler->addClientJob($request);
                return response()->json($response);
            }
    
        }catch(\Exception $e){
            return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        }

    }


    public function clientJob()
    {
        try{
            $response = $this->jobHandler->clientJobList();
            return response()->json($response);
    
        }catch(\Exception $e){
            return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        }
    }


    public function requestList()
    {
        try{
            $response = $this->jobHandler->jobRequestList();
            return response()->json($response);
    
        }catch(\Exception $e){
            return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
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
                return response()->json(["success" => false , "msg" => "Something Went Wrong" ,"error" => $validator->getMessageBag()] ,400);
            }else{
                $response = $this->jobHandler->addJobProposal($request);
                return response()->json($response);
            }
    
        }catch(\Exception $e){
            return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        }
    }

   public function awardedJobList()
   {
    try{
        $response = $this->jobHandler->awardedJobList();
        return response()->json($response);

    }catch(\Exception $e){
        return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
    }
   }

   public function getProposalList()
   {
        try{
            $response = $this->jobHandler->proposalList();
            return response()->json($response);

        }catch(\Exception $e){
            return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        }
   }

   public function getJobDetail(Request $request)
   {
    try{
        $validator = Validator::make( $request->all() , [
                                'job_id' => 'required|numeric'
                                    ]);
        if($validator->fails())
        {
            return response()->json(["success" => false , "msg" => "Something Went Wrong" ,"error" => $validator->getMessageBag()] ,400);
        }else{
            $response = $this->jobHandler->jobDetail($request);
            return response()->json($response);
        }

    }catch(\Exception $e){
        return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
    }
   }


   public function awardJob(Request $request)
   {
    try{
        $validator = Validator::make( $request->all() , [
                                        'job_id' => 'required|numeric',
                                        'request_id' => 'required|numeric'
                                    ]);
        if($validator->fails())
        {
            return response()->json(["success" => false , "msg" => "Something Went Wrong" ,"error" => $validator->getMessageBag()] ,400);
        }else{
            $response = $this->jobHandler->awardClientJob($request);
            return response()->json($response);
        }

    }catch(\Exception $e){
        return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
    }
   }


   public function getJobList(Request $request)
   {

    try{
        $response = $this->jobHandler->getEditorJobs();
        return response()->json($response);
    }catch(\Exception $e){
        return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
    }
   }

   public function getUnassignedJobs(){
    try{
        $response = $this->jobHandler->unassignedJobs();
        return response()->json($response);
    }catch(\Exception $e){
        return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
    }

   }

   public function cancelAwardedJob(Request $request)
   {
        try{
            $validator = Validator::make( $request->all() , [
                'job_id' => 'required|numeric',
            ]);

            if($validator->fails())
            {
                return response()->json(["success" => false , "msg" => "Something Went Wrong" ,"error" => $validator->getMessageBag()] ,400);
            }else{
                $response = $this->jobHandler->cancelJob($request);
                return response()->json($response);
            }
    
        }catch(\Exception $e){
            return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        }
   }


   public function cancelJobs(Request $request)
   {
    try{

        $response = $this->jobHandler->cancelJobList();
        return response()->json($response);

    }catch(\Exception $e){
        return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
    }
   }

   public function doneAwardedJob(Request $request)
   {
        try{

            $response = $this->jobHandler->doneJob($request);
            return response()->json($response);

        }catch(\Exception $e){
            return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        }
   }


   public function doneJobs(Request $request){
        try{

            $response = $this->jobHandler->doneJobList();
            return response()->json($response);

        }catch(\Exception $e){
            return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        }
   }

   public function postJobList(){
        try{

            $response = $this->jobHandler->clientPostedJobList();
            return response()->json($response);

        }catch(\Exception $e){
            return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        }
   }

   public function awardJobList(){
        try{

            $response = $this->jobHandler->clientAwardedJobList();
            return response()->json($response);

        }catch(\Exception $e){
            return response()->json(["success" => false , "msg" => "Something Went Wrong", "error" => $e->getMessage()] ,400);
        }
   }

   public function jobRequestList(Request $request){

        $validator = Validator::make( $request->all() , [
            'job_id' => 'required|numeric',
        ]);

        if($validator->fails())
        {
            return response()->json(["success" => false , "msg" => "Something Went Wrong" ,"error" => $validator->getMessageBag()] ,400);
        }else{
            $response = $this->jobHandler->jobEditorRequest($request);
            return response()->json($response);
        }

   }
   


}
