<?php

namespace App\Http\Repository;

use App\Http\AppConst;
use App\Models\{EditorRequest, PersonalJob , Skill , JobProposal, JobPayment , User , Folder, Review};
use Illuminate\Support\Facades\DB;

class JobHandler{

    public function addClientJob($request)
    {
        $title = $request->title;
        $description = $request->description;
        $budget = $request->budget;
        $deadline = $request->deadline;
        $folder = $request->folder_id;
        $quickDelivery = $request->quick_delivery;
        $jobId = PersonalJob::insertGetId([
                "client_id" => auth()->user()->id	,
                "title" => $title,	
                "description" => $description,	
                "budget" => $budget,
                "deadline" => $deadline,
                "folder_id" => $folder,	
                "status" => "unawarded",
                "quick_delivery" => $quickDelivery
            ]);
            
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

        $previousProposal = EditorRequest::where(["job_id" => $jobId , "editor_id" => $editorId , "status" => AppConst::UN_AWARDED_JOB ])->count();

        if($previousProposal){
            return ["success" => false , "msg" => "You have already added a proposal"];
        }

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
                                ->whereIn('personal_jobs.status' ,  ['Awarded' , 'awarded' , 'completed' , 'Completed', 'canceled' , 'Canceled'])
                                ->whereIn('job_editor_request.status' , [AppConst::AWARDED_JOB , AppConst::DONE_JOB , AppConst::CANCEL_JOB])
                                ->whereIn('requests.status' , [AppConst::AWARDED_JOB , AppConst::DONE_JOB , AppConst::CANCEL_JOB])
                                ->selectRaw('users.id as editor_id , users.profile_image, users.full_name , personal_jobs.id as job_id, personal_jobs.status as job_status, job_editor_request.id as proposal_id, personal_jobs.deadline, personal_jobs.title, personal_jobs.budget, personal_jobs.description as job_description, requests.bid_price, requests.description as proposal_detail, personal_jobs.awarded_date')
                                ->orderBy('personal_jobs.id' , 'desc')
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
                            ->join('users as client','client.id' , '=' , 'personal_jobs.client_id')
                            ->selectRaw(' users.id as editor_id , client.full_name as client_name , personal_jobs.title as job_title ,personal_jobs.deadline , personal_jobs.description as job_description , requests.description as proposal_detail , requests.bid_price')
                            ->where('job_editor_request.editor_id' , $editorId)
                            ->orderBy('job_editor_request.id' , 'desc')
                            ->get();    
            
        
        return ['success' => true , 'proposals' => $proposalList];
    }


    public function jobDetail($request)
    {
        $jobId = $request->job_id;
        
        $personalJob = PersonalJob::with('skills' , 'jobFolder.files' , 'review')->where('id' , $jobId)->first();

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
        ->join('users as editor' , 'job_editor_request.editor_id' ,'=' ,'editor.id')
        ->join('users as client' , 'personal_jobs.client_id' , '=' , 'client.id')
        ->join('requests' , 'job_editor_request.request_id' , '=' , 'requests.id')
        ->where('job_editor_request.status' ,'=' , 1)
        ->where('job_editor_request.editor_id' , '=' , auth()->user()->id)
        ->selectRaw('personal_jobs.id as job_id, client.full_name as client_name, client.profile_image as client_image , personal_jobs.title as job_title,personal_jobs.status as job_status,  personal_jobs.description as job_description, personal_jobs.deadline, requests.bid_price , folders.id')
        ->get();  
        
        // client.full_name as client_name, client.profile_image as client_image,

$profileImageLinkPrefix = asset('uploads');
return ['status' => true , 'jobs' => $jobs , 'profileImageLinkPrefix' => $profileImageLinkPrefix];
    }


    public function unassignedJobs(){
        $unassignedJobs = PersonalJob::with('skills' , 'user')->where('status' , 'unawarded')->get();
        return $unassignedJobs;
    }

    public function cancelJob($request){
        
        $jobId = $request->job_id;

        PersonalJob::where('id' , $jobId)->update(['status' => 'canceled']);

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
                                    ->where('status' , AppConst::AWARDED_JOB)
                                    ->first();

        JobProposal::where('id' , $editorRequest->request_id)
                        ->where('status' , 1)
                        ->update(['status' => AppConst::DONE_JOB]);

        $editorRequest->status = AppConst::DONE_JOB;
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

        $postedJobs = PersonalJob::withCount('requestList')->where('client_id' , $userId)->where('status' , 'unawarded')->get();

        return ["success" => true , 'postedJobs' => $postedJobs];

    }

    public function clientAwardedJobList()
    {
        $userId = auth()->user()->id;

        $awardedJobs = PersonalJob::with(['requestList' => function($query){
                                            return $query->with('editor')->whereIn('status' , [AppConst::DONE_JOB , AppConst::AWARDED_JOB]);
                                        }])
                                        ->whereIn('status' , ['awarded' , 'completed'])
                                        ->where('client_id' , $userId)
                                        ->get();

        // $awardedJobs = PersonalJob::with('awardedRequest.editor', 'review')->where('client_id' , $userId)->where('status' , 'awarded')->get();

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

    public function getOngoingJob(){
        
      $ongoingJobs =  PersonalJob::with('awardedRequest.editor' , 'review' )->where('client_id' , auth()->user()->id)->where('status' , 'awarded')->get();

      return response()->json(['status' => true , 'ongoingJobs' => $ongoingJobs]);

    }

    public function getCompletedJob(){

        $completedJobs =  PersonalJob::with('doneRequest.editor' , 'review' )->where('client_id' , auth()->user()->id)->where('status' , 'completed')->get();

        return response()->json(['status' => true , 'completedJobs' => $completedJobs]);

    }

    
    public function getJobReview($request){

        $jobReview = Review::where('job_id' , $request->job_id)->first();

        return response()->json(['status' => true , 'jobReview' => $jobReview]);
    
    }

    public function deleteJob($request){
        $jobId = $request->job_id;

        PersonalJob::where('id' , $jobId)->delete();

        return ["status" => true , "msg" => "Job Deleted Successfully"];

    }

    public function getFilteredJob($request){

        $lowerRange = $request->lower_range;
        $upperRange = $request->upper_range;
        $title = $request->title;
        $skills = json_decode($request->skills);
        $status = $request->status;


        $query = PersonalJob::query();
        
        $query->with('user' , 'skills' , 'awardedRequest.editor' , 'unawardedRequest.editor', 'awardedRequest.proposal' , 'unawardedRequest.proposal');

        $query->when(isset($title) && !is_null($title) , function($query1) use($title){
            $query1->where('title' , 'like' , '%'.$title.'%');
        });

        $query->when(isset($lowerRange) && !is_null($lowerRange) , function($query1) use ($lowerRange) {
            $query1->where('budget' , '>=' , $lowerRange);
        });

        $query->when(isset($upperRange) && !is_null($upperRange) , function($query1) use ($upperRange) {
            $query1->where('budget' , '<=' , $upperRange);
        });

        $query->when(isset($skills) && count($skills) > 0 , function($query1) use ($skills){
            $query1->whereHas('skills' , function($query2) use($skills){
                $query2->whereIn('title' , $skills);
            });
        });

        $query->when(isset($status) && !is_null($status) , function($query1) use ($status){
            $query1->where('status' , $status);
        });

        $jobs = $query->orderby('id' , 'desc')->get();

        return ['jobs' => $jobs , 'status' => true];
        

    }



}


 



