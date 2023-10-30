<?php

namespace App\Http\Repository;

use App\Http\AppConst;
use App\Models\{EditorRequest, PersonalJob , Skill , JobProposal, JobPayment , User , Folder };
use Illuminate\Support\Facades\DB;

class JobHandler{

    public function addClientJob($request)
    {
        $title = $request->title;
        $description = $request->description;
        $budget = $request->budget;
        $deadline = $request->deadline;
        $folder = $request->folder_id;
        
        $jobId = PersonalJob::insertGetId(
            [
                "client_id" => auth()->user()->id	,
                "title" => $title,	
                "description" => $description,	
                "budget" => $budget,
                "deadline" => $deadline,
                "folder_id" => $folder,	
                "status" => "unawarded"
                ]
            );
            
        $skills = json_decode($request->skills);
        $skillable = "App\Models\PersonalJob";
        $skillList =[];
        foreach($skills as $skill)
        {
            $skillList[] =["title" => $skill , "skillable_id" => $jobId , "skillable_type" => $skillable];
        }

        Skill::insert($skillList);

        return ["success" => true , "msg" => "Job Added Successfully"];

    }

    public function clientJobList()
    {
        $clientId = auth()->user()->id;

        $jobList = PersonalJob::where("client_id" , $clientId )->orderBy('id' , 'desc')->get();

        return ['success' => true , 'jobs' => $jobList];

    }


    public function jobRequestList()
    {
        $clientId = auth()->user()->id;

        $jobList = DB::table('personal_jobs')
                         ->join( 'job_editor_request' , 'personal_jobs.id' , '=' , 'job_editor_request.job_id')
                         ->join('users' , 'users.id' , '=' , 'job_editor_request.editor_id')
                         ->join('requests' , 'requests.id' , '=' , 'job_editor_request.request_id')
                        //  ->leftJoin('skills', 'job_editor_request.editor_id', '=', 'skills.skillable_id')
                         ->where('personal_jobs.client_id' , $clientId )
                         ->where('personal_jobs.status' , 'unawarded')
                         ->where('requests.status', '!=' , 2)
                         ->where('job_editor_request.status' ,'!=' , 2)
                         ->selectRaw('requests.id as request_id, personal_jobs.id as job_id , job_editor_request.id as proposal_id , personal_jobs.deadline ,  personal_jobs.title , personal_jobs.budget , personal_jobs.description as job_description , requests.bid_price , requests.description as proposal_detail, users.full_name as editor_name, users.email as editor_email, users.profile_image as editor_profile_image' )
                         ->selectSub(function($query){
                            $query->selectRaw('GROUP_CONCAT(skills.title) as editor_skills')
                                  ->from('skills')
                                  ->whereRaw('job_editor_request.editor_id = skills.skillable_id')
                                  ->where('skillable_type', 'App\Models\User');
                         } , 'editor_skills')
                         ->get();


        return ['success' => true , 'request' => $jobList];

    }

    public function addJobProposal($request)
    {
        $editorId = auth()->user()->id;
        $jobId = $request->job_id;
        $description = $request->description;
        $bidPrice = $request->bid_price;

        $requestId = JobProposal::insertGetId([
                        "description" => $description,
                        "bid_price" => $bidPrice
                    ]);

        EditorRequest::create([
            "editor_id" => $editorId,
            "job_id" => $jobId,
            "request_id" => $requestId,
            "status" => 0
        ]);


        return ["success" => true , "msg" => "Request Added Successfully"];



    }


    public function awardedJobList()
    {
        $clientId = auth()->user()->id;
        $awardedJobs = DB::table('personal_jobs')
                                ->join( 'job_editor_request' , 'personal_jobs.id' , '=' , 'job_editor_request.job_id')
                                ->join('users' , 'users.id' , '=' , 'job_editor_request.editor_id')
                                ->join('requests' , 'requests.id' , '=' , 'job_editor_request.request_id')
                                ->where('personal_jobs.client_id' , $clientId)
                                ->where('personal_jobs.status' ,  'awarded')
                                ->where('job_editor_request.status' , 1)
                                ->where('requests.status' , 1)
                                ->selectRaw('personal_jobs.id as job_id, job_editor_request.id as proposal_id, personal_jobs.deadline, personal_jobs.title, personal_jobs.budget, personal_jobs.description as job_description, requests.bid_price, requests.description as proposal_detail, personal_jobs.awarded_date')
                                ->get();
                                // ->unique('job_id')
                                // ->toSql();
        return ["success" => true , "awardedJobs" => $awardedJobs];
                

    }

    public function proposalList()
    {
        $editorId = auth()->user()->id;

        $proposalList = DB::table("personal_jobs")
                            ->join("job_editor_request" , "job_editor_request.job_id", "=" , "personal_jobs.id")
                            ->join("requests" , "requests.id" , "=" , "job_editor_request.request_id")
                            ->join("users" , "users.id" , "=" , "job_editor_request.editor_id")
                            ->selectRaw(' users.id as editor_id , personal_jobs.title as job_title ,personal_jobs.deadline , personal_jobs.description as job_description , requests.description as proposal_detail , requests.bid_price')
                            ->where('job_editor_request.editor_id' , $editorId)
                            ->get();    
            
        
        return ['success' => true , 'proposals' => $proposalList];
    }


    public function jobDetail($request)
    {
        $jobId = $request->job_id;
        
        $personalJob = PersonalJob::with('skills' , 'jobFolder.files')->where('id' , $jobId)->first();

        return ["success" => true , "jobDetail" => $personalJob];
    }

    public function awardClientJob($request)
    {
        try{    
            $jobId = $request->job_id;
            $requestId = $request->request_id;

            EditorRequest::where(
              [  
                ['job_id' , $jobId],
                ['request_id' , $requestId]
              ]
            )->update(['status' => 1]);

            PersonalJob::where( 'id' , $jobId )->update(['status' => 'awarded' , 'awarded_date' => date('Y-m-d H:i:s')]);

            JobProposal::where('id' , $requestId)->update(['status' => 1]);

            JobPayment::create(['job_id' => $jobId , 'request_id' => $requestId , 'client_transfer_status' => AppConst::CLIENT_PENDING , 'editor_transfer_status' => AppConst::EDITOR_PENDING]);
            
            return ['success' => true , 'msg' => 'Job Awarded Successfully'];

        }catch(\Exception $e){
            return ['success' => true , 'msg' => $e->getMessage()];
        }
    }


    public function getEditorJobs()
    {
    
        $jobs = DB::table('personal_jobs')
                    ->join('job_editor_request', 'job_id' ,'=' , 'personal_jobs.id'  )
                    ->join('folders' , 'folders.id' , '=' , 'personal_jobs.folder_id' )
                    ->join('users' , 'job_editor_request.editor_id' ,'=' ,'users.id')
                    ->join('requests' , 'job_editor_request.request_id' , '=' , 'requests.id')
                    ->where('job_editor_request.status' ,'=' , 1)
                    ->where('job_editor_request.editor_id' , '=' , auth()->user()->id)
                    ->selectRaw('personal_jobs.id as job_id, personal_jobs.title as job_title,  personal_jobs.description as job_description, personal_jobs.deadline, requests.bid_price , folders.id')
                    ->get();       

      return $jobs;
    }


    public function unassignedJobs(){
        $unassignedJobs = PersonalJob::where('status' , 'unawarded')->get();
        return $unassignedJobs;
    }

    public function cancelJob($request){
        
        $jobId = $request->job_id;

        PersonalJob::where('id' , $jobId)->update(['status' => 'unawarded']);

        $requests = EditorRequest::where('job_id' , $jobId)
                                    ->where('status' , 1)
                                    ->get();
        
        foreach($requests as $editorRequest)
        {
            $requestId = $editorRequest->request_id;
            JobProposal::where('id' , $requestId)
                        ->where('status' , 1)
                        ->update(['status' => 2]);
            $editorRequest->status = 2;
            $editorRequest->save();
        }

        
        return ['success' => true , 'msg' => 'Job Canceled Successfully'];
        
    }


    public function doneJob($request){
        $jobId = $request->job_id;

        PersonalJob::where('id' , $jobId)->update(['status' => 'completed']);

        $editorRequest = EditorRequest::where('job_id' , $jobId)
                                    ->where('status' , 1)
                                    ->first();

        JobProposal::where('id' , $editorRequest->request_id)
                        ->where('status' , 1)
                        ->update(['status' => 3]);

        $editorRequest->status = 3;
        $editorRequest->save();

        return ['success' => true , 'msg' => 'Job Done Successfully'];
    }


    public function cancelJobList()
    {
        $userId = auth()->user()->id;
        $cancelJobs = User::with('cancelJob.job')->where('id', $userId)->get();
        return ["success" => true , "cancelJobs" => $cancelJobs];
    }


    public function doneJobList()
    {
        $userId = auth()->user()->id;

        $doneJobs = User::with('doneJob.job.review')->where('id', $userId)->get();
        
        return ["success" => true , "doneJobs" => $doneJobs];
    }

    public function clientPostedJobList()
    {
        $userId = auth()->user()->id;

        $postedJobs = PersonalJob::where('client_id' , $userId)->where('status' , 'unawarded')->get();

        return ["success" => true , 'postedJobs' => $postedJobs];

    }

    public function clientAwardedJobList()
    {
        $userId = auth()->user()->id;

        $awardedJobs = PersonalJob::with('awardedRequest.editor')->where('client_id' , $userId)->where('status' , 'awarded')->get();

        return ["success" => true , 'awarededJobs' => $awardedJobs];

    }

    public function jobEditorRequest($request)
    {
        $jobId = $request->job_id;

        $jobRequest = PersonalJob::with('requestList.proposal','requestList.favourite' , 'requestList.editor.skills')->where('id' , $jobId)->first();

        return ["success" => true , 'jobRequest' => $jobRequest];
        
    }

    public function awardedJobEditorRequest($request)
    {
        $jobId = $request->job_id;

        $jobRequest = PersonalJob::with('awardedRequest.proposal','awardedRequest.favourite' , 'awardedRequest.editor.skills')->where('id' , $jobId)->first();

        return ["success" => true , 'jobRequest' => $jobRequest];
        
    }

    public function unawardedJobRequest($request){

        $jobId = $request->job_id;

        $jobRequest = PersonalJob::with('unawardedRequest.proposal','unawardedRequest.favourite' , 'unawardedRequest.editor.skills')->where('id' , $jobId)->first();

        return ["success" => true , 'unawardedJobRequest' => $jobRequest];
    }

    




}


 



