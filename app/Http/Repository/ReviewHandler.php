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
        $reviewList = Review::with(['job' => function($query){
                        $query->whereHas('awardedRequest' , function($query1){
                            return $query1->where('editor_id' , auth()->user()->id);
                        })->with('awardedRequest.proposal');
                    }])
                    ->orderBy('id' , 'desc')
                    ->get();
        $totalReviews = $reviewList->count();

        return ['success' => true , 'reviewList' => $reviewList , 'totalReviews' => $totalReviews];
    }

}