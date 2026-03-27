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

    public function accountStatus()
    {
        try {
            $editor = auth()->user();
            $status = $this->stripeService->checkEditorAccountStatus($editor);

            if ($status['enabled']) {
                return response()->json([
                    'success' => true,
                    'enabled' => true,
                    'msg' => 'Stripe account is fully active',
                ]);
            }

            return response()->json([
                'success' => true,
                'enabled' => false,
                'msg' => 'Your Stripe account is not fully set up. Please complete onboarding to bid on jobs.',
                'reason' => $status['reason'] ?? null,
                'acccount_link' => $status['acccount_link'],
            ]);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'msg' => "Something Went Wrong", "error" => $e->getMessage()], 400);
        }
    }
}
