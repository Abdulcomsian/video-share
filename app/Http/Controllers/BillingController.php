<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Repository\StripeService;

class BillingController extends Controller
{
    protected $stripe;

    public function __construct(StripeService $stripe)
    {
        $this->stripe = $stripe;    
    }

    public function payBill(Request $request)
    {
        try{
            $validator = Validator::make($request->all() , [
                'amount' => 'required|numeric',
                'cvc' => 'required',
                'card_exp_month' => 'required',
                'card_exp_year' => 'required',
                'card_number' => 'required'
            ]);

            if($validator->fails())
            {
                return response()->json(["success" => false , "msg" => "Something Went Wrong" ,"error" => $validator->getMessageBag()] , 400);
            }else{
                $response = $this->stripe->partialPayment($request);

                return response()->json($response);
            }

        }catch(\Exception $e){
            return response()->json(["success" => false , "error" => $e->getMessage()] , 401);
        }
    }


    public function processBilling(Request $request){
        try{
            $validator = Validator::make($request->all() , [
                'request_id' => 'required',
                'job_id' => 'required',
                'payment_method' => 'required' 
                // 'cvc' => 'required',
                // 'card_exp_month' => 'required',
                // 'card_exp_year' => 'required',
                // 'card_number' => 'required'
            ]);

            if($validator->fails())
            {
                return response()->json(["success" => false , "msg" => "Something Went Wrong" ,"error" => $validator->getMessageBag()] , 400);

            }else{

                $response = $this->stripe->processPayment($request);

                return response()->json($response);
            }

        }catch(\Exception $e){
            return response()->json(["success" => false , "error" => $e->getMessage()] , 400);
        }


    }


    public function getBillingPage(){
        return view('admin.billing');
    }

    public function processBillingFees(Request $request){
        try{
            $validator = Validator::make($request->all() , [
                'request_id' => 'required',
                'job_id' => 'required',
                'payment_method' => 'required' 
                // 'cvc' => 'required',
                // 'card_exp_month' => 'required',
                // 'card_exp_year' => 'required',
                // 'card_number' => 'required'
            ]);

            if($validator->fails())
            {
                return response()->json(["success" => false , "msg" => "Something Went Wrong" ,"error" => $validator->getMessageBag()] , 400);

            }else{

                $response = $this->stripe->processPayment($request);
                dd($response);
                return response()->json($response);
            }

        }catch(\Exception $e){
            dd($e->getMessage());
            return response()->json(["success" => false , "error" => $e->getMessage()] , 400);
        }
    }

    public function getPublicKey()
    {
        try {
            $response =  $this->stripe->getPublishableKey();

            return response()->json($response);

        }catch(\Exception $e){
            return response()->json(["success" => false , "error" => $e->getMessage()] , 400);
        }
    }

    public function getPaymentIntent(Request $request){
        try{
            $validator = Validator::make($request->all() , [
                'job_id' => 'required',
            ]);

            if($validator->fails())
            {
                return response()->json(["success" => false , "msg" => "Something Went Wrong" ,"error" => $validator->getMessageBag()] , 400);

            }else{

                $response = $this->stripe->createPaymentIntent($request);
                if($response['success'] == false){
                    return response()->json($response , 400);
                }
                return response()->json($response);
            }


        }catch(\Exception $e){
            return response()->json(["success" => false , "error" => $e->getMessage()] , 400);
        }
    }

}

