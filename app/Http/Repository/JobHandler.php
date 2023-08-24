<?php

namespace App\Http\Repository;
use App\Models\{EditorRequest, PersonalJob , Skill , JobProposal};
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
                         ->where('personal_jobs.client_id' , $clientId)
                         ->selectRaw('personal_jobs.id as job_id , job_editor_request.id as proposal_id , personal_jobs.deadline ,  personal_jobs.title , personal_jobs.budget , personal_jobs.description as job_description , requests.bid_price , requests.description as proposal_detail')
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
                                ->where('personal_jobs.status' , 'LIKE' , '%awarded%')
                                ->selectRaw('personal_jobs.id as job_id , job_editor_request.id as proposal_id , personal_jobs.deadline ,  personal_jobs.title , personal_jobs.budget , personal_jobs.description as job_description , requests.bid_price , requests.description as proposal_detail')
                                ->get();

        
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
        
        $personalJob = PersonalJob::with('skills')->where('id' , $jobId)->first();

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

            PersonalJob::where( 'id' , $jobId )->update(['status' => 'awarded']);

            return ['success' => true , 'msg' => 'Job Awarded Successfully'];

        }catch(\Exception $e){
            return ['success' => true , 'msg' => $e->getMessage()];
        }
    }


    public function getEditorJobs()
    {
    
        $jobs = DB::table('personal_jobs')
                    ->join('job_editor_request', 'job_id' ,'=' , 'personal_jobs.id'  )
                    ->join('users' , 'job_editor_request.editor_id' ,'=' ,'users.id')
                    ->join('requests' , 'job_editor_request.request_id' , '=' , 'requests.id')
                    ->where('job_editor_request.status' ,'=' , 1)
                    ->where('job_editor_request.editor_id' , '=' , auth()->user()->id)
                    ->selectRaw('personal_jobs.id as job_id, personal_jobs.title as job_title,  personal_jobs.description as job_description, personal_jobs.deadline, requests.bid_price')
                    ->get();       

      return $jobs;
    }


    public function unassignedJobs(){
        $unassignedJobs = PersonalJob::where('status' , 'unawarded')->get();
        return $unassignedJobs;
    }


}


 



