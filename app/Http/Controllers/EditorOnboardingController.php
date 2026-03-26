<?php

namespace App\Http\Controllers;

use App\Http\Repository\{EditorHandler, UserHandler, StripeService};
use App\Models\EditorStripePaymentMethod;
use Stripe\{Stripe, Account, AccountLink};
use Illuminate\Http\Request;

class EditorOnboardingController extends Controller
{
    protected $stripeService, $userHandler, $editorHandler;

    public function __construct(StripeService $stripeService, UserHandler $userHandler, EditorHandler $editorHandler)
    {
        $this->stripeService = $stripeService;
        $this->userHandler = $userHandler;
        $this->editorHandler = $editorHandler;
    }

    public function onBoardingSuccess(Request $request)
    {
        $accountId = $request->query('account_id'); // Get account ID from query params

        if (!$accountId) {
            throw new \Exception("Account ID is missing.", 1);
        }

        try {

            $user = $this->userHandler->getRowByColumns(['stripe_account_id' => $accountId]);

            return view('stripe.onboarding-success', compact('user'));
        } catch (\Exception $e) {
            return redirect()->route('stripe.onboarding.error', ['account_id' => $accountId])->with('error', $e->getMessage());
        }
    }

    public function refreshOnboarding(Request $request)
    {

        $accountId = $request->query('account_id'); // Get account ID from query params

        if (!$accountId) {
            throw new \Exception("Account ID is missing.", 1);
        }

        try {

            $accountLink = $this->stripeService->createEditoryOnboardingAccountLink($accountId);

            return redirect($accountLink->url); // Redirect user back to Stripe onboarding

        } catch (\Exception $e) {
            return redirect()->route('stripe.onboarding.error', ['account_id' => $accountId])->with('error', $e->getMessage());
        }
    }

    public function stripeSuccess(Request $request)
    {

        $accountId = $request->query('account_id'); // Get account ID from query params

        if (!$accountId) {
            throw new \Exception("Account ID is missing.", 1);
        }


        try {

            $account = $this->stripeService->getEditorStripeDetailsById($accountId);

            // Check if onboarding is complete
            if (
                empty($account->requirements->disabled_reason) &&
                empty($account->requirements->currently_due) &&
                empty($account->requirements->past_due)
                ) {

                // get external accounts
                $externalAccounts = $this->stripeService->getAllEditorExternalAccountsByAccountType($accountId);
                $user = $this->userHandler->getRowByColumns(['stripe_account_id' => $accountId]);

                if ($externalAccounts->data) {
                    foreach ($externalAccounts->data as $externalAccount) {
                        $paymentMethod = $this->editorHandler->getEditorPaymentMethodById($externalAccount->id);

                        if (!$paymentMethod) {

                            $arr = [
                                'user_id' => $user->id,
                                'stripe_payment_method_id' => $externalAccount->id,
                                'type' => $externalAccount->object
                            ];

                            EditorStripePaymentMethod::create($arr);

                        }
                    }
                }

                // // update merchant onboarding status
                $this->userHandler->updateUserByConditions([
                    'onboarding' => 1
                ], ['stripe_account_id' => $accountId]);

                return redirect()->route('stripe.onboarding.success', ['account_id' => $accountId])->with('success', 'Onboarding completed successfully!');
            } else {
                return redirect()->route('stripe.onboarding.error')->with('error', 'Onboarding incomplete. Please try again.');
            }

        } catch (\Exception $e) {
            return redirect()->route('stripe.onboarding.error')->with('error', $e->getMessage());
        }
    }

    public function onBoardingError()
    {
        return view('stripe.onboarding-error');
    }
}
