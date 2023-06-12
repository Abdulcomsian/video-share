<?php

namespace App\Http\Repository;
use App\Models\{EditorRequest, PersonalJob , Skill , JobProposal};

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
        $skillable = "Job";
        $skillList =[];
        foreach($skills as $skill)
        {
            $skillList[] =["title" => $skill , "skillable_id" => $jobId , "skillable_url" => $skillable];
        }

        Skill::insert($skillList);

        return ["success" => true , "msg" => "Job Added Successfully"];

    }

    public function clientJobList()
    {
        $clientId = auth()->user()->id;

        $jobList = PersonalJob::with("")->where("client_id" , $clientId )->orderBy('id' , 'desc')->get();

        return ['success' => true , 'jobs' => $jobList];

    }


    public function jobRequestList()
    {
        $clientId = auth()->user()->id;

        $requestList = PersonalJob::where("client_id" , $clientId )->with('allEditor.proposal')->orderBy('id' , 'desc')->get();

        return ['success' => true , 'request' => $requestList];

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
            "request_id" => $requestId
        ]);


        return ["success" => true , "msg" => "Request Added Successfully"];



    }


    public function awardedJobList()
    {
        $clientId = auth()->user()->id;
        $awardedJobs = PersonalJob::with(['allEditor.proposal' => function($query){
                                        $query->where('awarded' , 1 );
                                    }])
                                    ->where('client_id' , $clientId )
                                    ->get();
        
        dd($awardedJobs);
        return ["success" => true , "awardedJobs" => $awardedJobs];
                

    }


}