<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Repository\ReviewHandler;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    protected $reviewHandler;

    function __construct(ReviewHandler $reviewHandler){
        $this->reviewHandler = $reviewHandler;
    } 

    function addUpdateReview(Request $request){
        try{
            $validator = Validator::make($request->all() , [
                                            'job_id' => 'required|numeric',
                                            'rating' => 'required|numeric'
                                        ]);
            
            if($validator->fails()){

                return response()->json(['success' => false , 'msg' => 'Something Went Wrong' , 'error' => $validator->getMessageBag()]);
            
            }else{
                $response = $this->reviewHandler->jobReview($request);

                return response()->json($response);
            }

        }catch(\Exception $e){

            return response()->json(['success' => false , 'msg' => 'Something Went Wrong' , 'error' => $e->getMessage()]);
        
        }
    }

}
