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

    public function getPaymentIntent(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'request_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(["success" => false, "msg" => "Something Went Wrong", "error" => $validator->getMessageBag()], 400);
            }

            $response = $this->stripe->createPaymentIntent($request);

            if (!$response['success']) {
                return response()->json($response, 400);
            }

            return response()->json($response);

        } catch (\Exception $e) {
            return response()->json(["success" => false, "error" => $e->getMessage()], 400);
        }
    }

    public function getPublicKey()
    {
        try {
            $response = $this->stripe->getPublishableKey();
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(["success" => false, "error" => $e->getMessage()], 400);
        }
    }
}
