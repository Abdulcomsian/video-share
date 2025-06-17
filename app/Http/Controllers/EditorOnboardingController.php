<?php

namespace App\Http\Controllers;

use app\Http\Repository\StripeService;
use App\Services\Contracts\{UserServiceInterface, MerchantServiceInterface};
use Stripe\{Stripe, Account, AccountLink};
use Illuminate\Http\Request;

class EditorOnboardingController extends Controller
{
    protected $stripeService;
    protected $userService;
    protected $merchantService;
    public function __construct(StripeService $stripeService, UserServiceInterface $userService, MerchantServiceInterface $merchantService)
    {
        $this->stripeService = $stripeService;
        $this->userService = $userService;
        $this->merchantService = $merchantService;
    }

    public function onBoardingSuccess(Request $request)
    {
        $accountId = $request->query('account_id'); // Get account ID from query params

        if (!$accountId) {
             info('f-onboarding onBoardingSuccess account ID is missing');
            throw new \Exception("Account ID is missing.", 1);
        }

        try {


            $stripeMerchant = $this->stripeService->getStripeMerchantById($accountId);
            $user = $this->userService->getRowByColumns(['stripe_id' => $accountId]);
            info('f-onboarding sucess');

            return view('stripe.onboarding-success', compact('user'));
        } catch (\Exception $e) {
            info('f-onboarding success error start');
            info($e->getMessage());
            info('f-onboarding success error end');
            return redirect()->route('stripe.onboarding.error')->with('error', $e->getMessage());
        }
    }

    public function refreshOnboarding(Request $request)
    {

        $accountId = $request->query('account_id'); // Get account ID from query params

        if (!$accountId) {
            info('f-onboarding refreshOnboarding account ID is missing');
            throw new \Exception("Account ID is missing.", 1);
        }

        try {

            $accountLink = $this->stripeService->createMerchantOnboardingAccountLink($accountId);

            info('f-onboarding referesh try');

            return redirect($accountLink->url); // Redirect user back to Stripe onboarding

        } catch (\Exception $e) {

            info('f-onboarding refreshOnboarding error start');
            info($e->getMessage());
            info('f-onboarding refreshOnboarding error end');
            return redirect()->route('stripe.onboarding.error')->with('error', $e->getMessage());
        }
    }

    public function stripeSuccess(Request $request)
    {

        $accountId = $request->query('account_id'); // Get account ID from query params

        if (!$accountId) {
            info('f-onboarding stripeSuccess account ID is missing');
            throw new \Exception("Account ID is missing.", 1);
        }

        try {

            info('f-onboarding stripe success try1');

            $account = $this->stripeService->getStripeMerchantById($accountId);

            info($account);
            info('f-onboarding stripe success after object get');
            // Check if onboarding is complete
            if (
                empty($account->requirements->disabled_reason) &&
                empty($account->requirements->currently_due) &&
                empty($account->requirements->past_due)
            ) {

                info('f-onboarding stripe success before store external accounts');
                // store external accounts
                $this->merchantService->storeExternalAccounts($accountId);

                info('f-onboarding stripe success after store external accounts');

                // update merchant onboarding status
                $this->merchantService->updateMerchantStatus($accountId);

                info('f-onboarding stripe success update merchant status');

                return redirect()->route('stripe.onboarding.success', ['account_id' => $accountId])->with('success', 'Onboarding completed successfully!');
            } else {

                info('f-onboarding stripe success error onboarding incomplete');
                return redirect()->route('stripe.onboarding.error')->with('error', 'Onboarding incomplete. Please try again.');
            }

        } catch (\Exception $e) {

            info('f-onboarding stripeSuccess error start');
            info($e->getMessage());
            info('f-onboarding stripeSuccess error end');
            return redirect()->route('stripe.onboarding.error')->with('error', $e->getMessage());
        }
    }

    public function onBoardingError()
    {
        info('onboarding error blade file');
        return view('stripe.onboarding-error');
    }
}
