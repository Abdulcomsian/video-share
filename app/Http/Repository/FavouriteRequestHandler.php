<?php

namespace App\Http\Repository;
use App\Models\{FavouriteRequest , PersonalJob};

class FavouriteRequestHandler{

    public function addFavouriteJobRequest($request){

       $requestId =  $request->request_id;
       $jobId = $request->job_id;


       $favouriteCount  = FavouriteRequest::where("editor_request_id" , $requestId )
                                            ->where("job_id" , $jobId)
                                            ->count();
       if($favouriteCount == 0){

           FavouriteRequest::create([
            "editor_request_id" => $requestId,
            "job_id" => $jobId
           ]);
       
       }


       return ["success" => true , "msg" => "Request Marked As Favourite"];
    }

    public function getFavouriteJobRequest($request){

        $jobId = $request->job_id;

        $favouriteList = PersonalJob::with('favouriteRequest.editorRequest.proposal' , 'favouriteRequest.editorRequest.editor')->where('id' , $jobId)->first();

        return ["success" => true , "JobFavouriteList" => $favouriteList];

    }

    public function removeFavouriteJobRequest($request)
    {
        $frId = $request->fr_id;

        FavouriteRequest::where('id' , $frId)->delete();

        return ["success" => true , "msg" => "Favourite Request Removed Successfully"];
    }

}