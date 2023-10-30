<?php

namespace App\Http\Repository;
use App\Models\Review;

class ReviewHandler{

    public function jobReview($request){

        $jobId = $request->job_id;
        $rating = $request->rating;

        Review::updateOrCreate(

            ['job_id' => $jobId],
            [  'job_id' => $jobId, 'rating' => $rating ]
        );

        return ['success' => true , 'msg' => 'Review Added Successfully'];
    }

}