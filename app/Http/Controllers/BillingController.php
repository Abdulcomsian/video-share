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
                $response = $this->stripe->processPayment($request);

                return response()->json($response);
            }

        }catch(\Exception $e){
            return response()->json(["success" => false , "error" => $e->getMessage()] , 401);
        }
    }
}
