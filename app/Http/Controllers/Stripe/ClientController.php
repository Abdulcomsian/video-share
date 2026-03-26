<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Http\Repository\StripeService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function setupIntent()
    {
        try {
            $setupIntent = $this->stripeService->createSetupIntent(auth()->user());

            return response()->json([
                'success' => true,
                'clientSecret' => $setupIntent->client_secret,
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        }
    }

    public function paymentMethods()
    {
        try {
            $methods = $this->stripeService->getPaymentMethods(auth()->user());

            $cards = array_map(function ($method) {
                return [
                    'id' => $method->id,
                    'brand' => $method->card->brand,
                    'last4' => $method->card->last4,
                    'exp_month' => $method->card->exp_month,
                    'exp_year' => $method->card->exp_year,
                ];
            }, $methods);

            return response()->json(['success' => true, 'payment_methods' => $cards]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        }
    }

    public function deletePaymentMethod(Request $request)
    {
        try {
            $request->validate(['payment_method_id' => 'required|string']);

            $this->stripeService->deletePaymentMethod($request->payment_method_id);

            return response()->json(['success' => true, 'msg' => 'Payment method removed']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        }
    }
}
