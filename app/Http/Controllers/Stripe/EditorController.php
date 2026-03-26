<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Http\Repository\{StripeService, UserHandler};
use Illuminate\Http\Request;
use Exception;

class EditorController extends Controller
{
    protected $stripeService, $userHandler;

    public function __construct(StripeService $stripeService, UserHandler $userHandler)
    {
        $this->stripeService = $stripeService;
        $this->userHandler = $userHandler;
    }

    public function createAccount(Request $request)
    {
        try {
            $data = $this->stripeService->editorOnboarding();

            return response()->json(['success' => true, 'msg' => "Onboarding link generated successfully", "data" => $data], 200);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'msg' => "Something Went Wrong", "error" => $e->getMessage()], 400);
        }
    }
}
