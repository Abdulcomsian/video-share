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

    public function reviewList(){
        $reviewList = Review::whereHas('job' , function($query){
                        $query->whereHas('doneRequest' , function($query1){
                            return $query1->where('editor_id' , auth()->user()->id);
                        });
                    })->with('job.doneRequest.proposal' , 'job.user')
                    ->orderBy('id' , 'desc')
                    ->get();
       
        $totalReviews = $reviewList->count();
        $averageRating = ($reviewList->sum('rating')) / $totalReviews;
        return ['success' => true , 'reviewList' => $reviewList , 'totalReviews' => $totalReviews , 'averageRating' => $averageRating];
    }

}