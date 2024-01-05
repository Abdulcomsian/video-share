<?php

namespace App\Http\Repository;
use App\Models\Review;

class ReviewHandler{

    public function jobReview($request){

        $jobId = $request->job_id;
        $rating = $request->rating;
        $comment = $request->comment;

        Review::updateOrCreate(

            ['job_id' => $jobId],
            [  'job_id' => $jobId, 'rating' => $rating , 'comment' => $comment ]
        );

        return ['success' => true , 'msg' => 'Review Added Successfully'];
    }

    public function reviewList($editorId){

        $reviewList = Review::whereHas('job' , function($query) use ($editorId){
                        $query->whereHas('doneRequest' , function($query1) use ($editorId){
                            return $query1->where('editor_id' , $editorId);
                        });
                    })->with('job.doneRequest.proposal' , 'job.user')
                    ->orderBy('id' , 'desc')
                    ->get();
       
        $totalReviews = $reviewList->count();
        $averageRating = $totalReviews > 0 ? ($reviewList->sum('rating')) / $totalReviews : 0 ;
        return ['success' => true , 'reviewList' => $reviewList , 'totalReviews' => $totalReviews , 'averageRating' => $averageRating];
    }

}